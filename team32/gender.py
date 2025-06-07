import argparse
import os
import time
import cv2
import numpy as np
from threading import Thread
import sys
from tflite_runtime.interpreter import Interpreter
import RPi.GPIO as GPIO

time.sleep(5)
os.system('espeak "Hello from espeak" hw:1,0')

# Define GPIO pins
TRIG_PIN = 27
ECHO_PIN = 22
BUZZER_PIN = 4

# Define distance threshold (in cm)
DISTANCE_THRESHOLD = 25

# Setup GPIO mode
GPIO.setmode(GPIO.BCM)

# Setup GPIO pins
GPIO.setup(TRIG_PIN, GPIO.OUT)
GPIO.setup(ECHO_PIN, GPIO.IN)
GPIO.setup(BUZZER_PIN, GPIO.OUT)

# Initialize trigger pin low
GPIO.output(TRIG_PIN, False)
print("Waiting for sensor to settle")
time.sleep(2)

video_driver_id = 0

class VideoStream:
    def _init_(self, resolution=(640, 480), framerate=30):
        self.stream = cv2.VideoCapture(video_driver_id)
        self.stream.set(cv2.CAP_PROP_FOURCC, cv2.VideoWriter_fourcc(*'MJPG'))
        self.stream.set(3, resolution[0])
        self.stream.set(4, resolution[1])
        self.grabbed, self.frame = self.stream.read()
        self.stopped = False

    def start(self):
        Thread(target=self.update, args=()).start()
        return self

    def update(self):
        while True:
            if self.stopped:
                self.stream.release()
                return
            self.grabbed, self.frame = self.stream.read()

    def read(self):
        return self.frame

    def stop(self):
        self.stopped = True

def load_labels(labelmap_path: str) -> list:
    try:
        with open(labelmap_path, 'r') as f:
            labels = [line.strip() for line in f.readlines()]
        if labels[0] == '???':
            labels.pop(0)
        return labels
    except IOError as e:
        print(f"Error reading label map file: {e}")
        sys.exit()

def load_gender_model(model_path: str):
    try:
        interpreter = Interpreter(model_path=model_path)
        interpreter.allocate_tensors()
        return interpreter
    except Exception as e:
        print(f"Error loading gender model: {e}")
        return None

def preprocess_gender_frame(frame, width, height):
    frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    frame_resized = cv2.resize(frame_rgb, (width, height))
    input_data = np.expand_dims(frame_resized, axis=0)
    input_data = (np.float32(input_data) - 127.5) / 127.5
    return input_data

last_spoken = {}
cooldown = 3  # seconds

def speak(text):
    def _speak():
        os.system(f'espeak "{text}"')
    Thread(target=_speak, daemon=True).start()

def speak_throttled(label):
    now = time.time()
    if label not in last_spoken or now - last_spoken[label] > cooldown:
        last_spoken[label] = now
        speak(label)

def measure_distance():
    GPIO.output(TRIG_PIN, True)
    time.sleep(0.00001)
    GPIO.output(TRIG_PIN, False)

    pulse_start_time = time.time()
    pulse_end_time = time.time()

    max_time = time.time() + 0.1
    while GPIO.input(ECHO_PIN) == 0:
        pulse_start_time = time.time()
        if pulse_start_time > max_time:
            return None

    max_time = time.time() + 0.1
    while GPIO.input(ECHO_PIN) == 1:
        pulse_end_time = time.time()
        if pulse_end_time > max_time:
            return None

    pulse_duration = pulse_end_time - pulse_start_time
    distance = (pulse_duration * 34300) / 2
    return distance

def trigger_buzzer(state):
    GPIO.output(BUZZER_PIN, state)
    print("Buzzer ON" if state else "Buzzer OFF")

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--modeldir', required=False)
    parser.add_argument('--graph', default='detect.tflite')
    parser.add_argument('--labels', default='labelmap.txt')
    parser.add_argument('--threshold', default='0.5')
    parser.add_argument('--resolution', default='1280x720')
    parser.add_argument('--gender_modeldir', required=False)
    parser.add_argument('--gender_graph', default='model_lite_gender_q.tflite')
    args = parser.parse_args()

    model_path = "/home/shreeshag/team32/TFLite_model/detect.tflite"
    labelmap_path = "/home/shreeshag/team32/TFLite_model/labelmap.txt"
    min_conf_threshold = float(args.threshold)
    resW, resH = map(int, args.resolution.split('x'))
    gender_model_path = "/home/shreeshag/team32/Gender_TFLite_model/model_lite_gender_q.tflite"

    labels = load_labels(labelmap_path)
    interpreter = Interpreter(model_path=model_path)
    interpreter.allocate_tensors()

    input_details = interpreter.get_input_details()
    output_details = interpreter.get_output_details()
    height, width = input_details[0]['shape'][1:3]
    floating_model = (input_details[0]['dtype'] == np.float32)

    outname = output_details[0]['name']
    boxes_idx, classes_idx, scores_idx = (1, 3, 0) if 'StatefulPartitionedCall' in outname else (0, 1, 2)

    videostream = VideoStream(resolution=(resW, resH), framerate=30).start()
    time.sleep(1)

    frame_rate_calc = 1
    freq = cv2.getTickFrequency()

    gender_interpreter = load_gender_model(gender_model_path)
    if gender_interpreter:
        gender_input_details = gender_interpreter.get_input_details()
        gender_output_details = gender_interpreter.get_output_details()
        gender_height, gender_width = gender_input_details[0]['shape'][1:3]
        gender_labels = ['Male', 'Female']
    else:
        gender_labels = []

    while True:
        distance = measure_distance()
        if distance is not None:
            print(f"Distance: {distance:.2f} cm")
            trigger_buzzer(distance < DISTANCE_THRESHOLD)
        else:
            print("Error: Could not measure distance")

        time.sleep(0.1)

        t1 = cv2.getTickCount()
        frame = videostream.read()
        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        frame_resized = cv2.resize(frame_rgb, (width, height))
        input_data = np.expand_dims(frame_resized, axis=0)

        if floating_model:
            input_data = (np.float32(input_data) - 127.5) / 127.5

        interpreter.set_tensor(input_details[0]['index'], input_data)
        interpreter.invoke()

        boxes = interpreter.get_tensor(output_details[boxes_idx]['index'])[0]
        classes = interpreter.get_tensor(output_details[classes_idx]['index'])[0]
        scores = interpreter.get_tensor(output_details[scores_idx]['index'])[0]

        unique_objects = set()
        unique_genders = set()

        for i in range(len(scores)):
            if min_conf_threshold < scores[i] <= 1.0:
                ymin, xmin, ymax, xmax = [int(coord) for coord in (boxes[i] * [resH, resW, resH, resW])]
                cv2.rectangle(frame, (xmin, ymin), (xmax, ymax), (10, 255, 0), 2)
                object_name = labels[int(classes[i])]
                label = f'{object_name}: {int(scores[i] * 100)}%'
                labelSize, baseLine = cv2.getTextSize(label, cv2.FONT_HERSHEY_SIMPLEX, 0.7, 2)
                label_ymin = max(ymin, labelSize[1] + 10)
                cv2.rectangle(frame, (xmin, label_ymin - labelSize[1] - 10),
                              (xmin + labelSize[0], label_ymin + baseLine - 10),
                              (255, 255, 255), cv2.FILLED)
                cv2.putText(frame, label, (xmin, label_ymin - 7),
                            cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 0), 2)

                unique_objects.add(object_name)

                if gender_interpreter:
                    print(f"Checking gender for: {object_name}")
                    try:
                        ymin_clamped = max(0, ymin)
                        xmin_clamped = max(0, xmin)
                        ymax_clamped = min(frame.shape[0], ymax)
                        xmax_clamped = min(frame.shape[1], xmax)

                        face_crop = frame[ymin_clamped:ymax_clamped, xmin_clamped:xmax_clamped]

                        if face_crop.size == 0 or face_crop.shape[0] == 0 or face_crop.shape[1] == 0:
                            continue

                        gender_input_data = preprocess_gender_frame(face_crop, gender_width, gender_height)
                        gender_interpreter.set_tensor(gender_input_details[0]['index'], gender_input_data)
                        gender_interpreter.invoke()
                        gender_scores = gender_interpreter.get_tensor(gender_output_details[0]['index'])[0]
                        gender_label = gender_labels[np.argmax(gender_scores)]

                        cv2.putText(frame, gender_label, (xmin_clamped, ymin_clamped - 10),
                                    cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 0, 0), 2)

                        unique_genders.add(gender_label)

                    except Exception as e:
                        print(f"Gender detection error: {e}")

        if unique_objects:
            obj_text = "Detected objects: " + ', '.join(sorted(unique_objects))
            print(obj_text)
            speak(obj_text)

        if unique_genders:
            gen_text = "Detected genders: " + ', '.join(sorted(unique_genders))
            print(gen_text)
            speak(gen_text)

        cv2.putText(frame, f'FPS: {frame_rate_calc:.2f}', (30, 50),
                    cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 0), 2, cv2.LINE_AA)

        t2 = cv2.getTickCount()
        time1 = (t2 - t1) / freq
        frame_rate_calc = 1 / time1

        if cv2.waitKey(1) == ord('q'):
            break

    cv2.destroyAllWindows()
    videostream.stop()

if _name_ == "_main_":
    main()