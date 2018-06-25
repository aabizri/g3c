/**
 * itoc converts an int to a char array
 */
void itoc(char *dst, int val, size_t size) {
    int bitmask;
    for (int i = 0; i <= size; i++) {
        bitmask = 0xFF << i;
        dst[i] = val & bitmask;
    }
}

/**
 * ctoi converts a char array to an int
 */
int ctoi(char *data, size_t size) {
    int val = 0;
    for (int i = 0; i <= size; i++) {
        val |= data[i] << i;
    }
    return val;
}

class SensorOrActuator {
public:
    char *num;

    SensorOrActuator(char *num) {
        this->num = num;
    }
};

class Sensor : public SensorOrActuator {
public:
    virtual void consult(char *val) = 0;

    Sensor(char *num) : SensorOrActuator(num) {};
};

class Actuator : public SensorOrActuator {
public:
    virtual void order(char *order) = 0;

    Actuator(char *num) : SensorOrActuator(num) {};
};

/**
* Bargraph interaction class
*/
class BarGraph : public Actuator {
public:
    char status = 0;

    int *ledsPins;

    void init() {
        for (int i = 0; i < 5; i++) {
            pinMode(ledsPins[i], OUTPUT);
        }
    }

    BarGraph(char *num, int *pins) : Actuator(num) {
        this->ledsPins = pins;
        init();
    }

    /**
    * get the configured value corresponding to the index
    */
    boolean get(int index) {
        return (status & (1 << index)) != 0;
    }

    /**
    * sets a value in the configuration but doesn't push it
    */
    void set(int index, boolean value) {
        if (value) {
            status = status | (1 << index);
        } else {
            status = status & ~(1 << index);
        }
    }

    /**
    * push the configuration to the hardware.
    * pushes starting from the lower pins
    */
    void push() {
        for (int i = 0; i < 5; i++) {
            pushPin(i);
        }
    }

    /**
    * resets the configuration, but doesn't push it
    */
    void reset() {
        status = 0;
    }

    /**
    * push the state of a single pin
    */
    void pushPin(int index) {
        int pin = ledsPins[index];
        int val = get(index) ? HIGH : LOW;
        digitalWrite(pin, val);
    }

    /**
     * order the bargraph
     */
    void order(char *val) {
        status = ctoi(val, 4);
        this->push();
    }
};

/**
* interpretation class for the Proximity Sensor
*/
class ProximitySensor : public Sensor {
public:
    int pin;

    void init() {
        pinMode(pin, INPUT);
    }

    ProximitySensor(char *num, int pin = DEFAULT_PROXIMITY_SENSOR_PIN) : Sensor(num) {
        init();
    }

    /**
    * Reads the value, on a scale of 0 to 4093
    * Warning: the value actually fluctuates from 680 to 2100.
    */
    int read() {
        return analogRead(pin);
    }

    void consult(char *val) {
        return itoc(val, this->read(), 4);
    }
};

/**
* Light detector, but doesn't measure it.
*/
class LightDetector : public Sensor {
public:
    int pin;
    static const DEFAULT_THRESHOLD = 2000;
    int threshold;

    void init() {
        pinMode(pin, INPUT);
    }

    LightDetector(char *num, int pin, int threshold = DEFAULT_THRESHOLD) : Sensor(num) {
        this->pin = pin;
        this->threshold = threshold;
        init();
    }

    /**
     * Reads the value from the sensor, on a scale of 0 to 4093
     *
     * isLight should be used instead
     */
    int read() {
        return analogRead(pin);
    }

    void consult(char *val) {
        itoc(val, this->read(), 4);
    }

    /**
    * Interpret the value given the threshold: is there light
    */
    boolean isLight() {
        return this->read() > threshold;
    }

    void setThreshold(int threshold) {
        this->threshold = threshold;
    }
};

/**
* interaction class for the motor
*/
class Motor : public Actuator {
public:
    int cwPin;
    int ccPin;

    static const int DEFAULT_STOP_DELAY = 10;
    int stopDelay;

    static const int STOPPED = 0;
    static const int CC = 1;
    static const int CW = 2;
    int currentState = STOPPED;

    void init() {
        pinMode(cwPin, OUTPUT);
        pinMode(ccPin, OUTPUT);
    }

    Motor(char *num, int cwPin, int ccPin, int stopDelay = DEFAULT_STOP_DELAY) : Actuator(num) {
        this->cwPin = cwPin;
        this->ccPin = ccPin;
        this->stopDelay = stopDelay;
        init();
    }

    void stop() {
        if (currentState == STOPPED) return;

        digitalWrite(ccPin, LOW);
        digitalWrite(cwPin, LOW);
        currentState = STOPPED;

        delay(stopDelay);
    }

    void reverse() {
        if (currentState == STOPPED) return;

        if (currentState == CC) runCW(); else runCC();
    }

    void runCW() {
        if (currentState == CW) return;
        if (currentState == CC) this->stop();

        digitalWrite(ccPin, LOW);
        digitalWrite(cwPin, HIGH);
        currentState = CW;
    }

    void runCC() {
        if (currentState == CC) return;
        if (currentState == CW) this->stop();

        digitalWrite(cwPin, LOW);
        digitalWrite(ccPin, HIGH);
        currentState = CC;
    }

    void order(char *val) {
        int ival = ctoi(val, 4);
        switch (ival) {
            case STOPPED:
                this->stop();
                break;
            case CC:
                this->runCC();
                break;
            case CW:
                this->runCW();
                break;
            default:
                break;
        }
    }
};

class Fan : public Actuator {
public:
    int pin;

    void init() {
        pinMode(pin, OUTPUT);
    }

    Fan(char *num, int pin) : Actuator(num) {
        this->pin = pin;
        init();
    }

    void start() {
        digitalWrite(pin, LOW);
    }

    void stop() {
        digitalWrite(pin, HIGH);
    }

    void order(char *val) {
        int ival = ctoi(val, 4);
        ival > 0 ? this->start() : this->stop();
    }
};


/**
 * Potentiometer provides monitoring of the potentiomeer
 */
class Potentiometer : public Sensor {
public
    int pin;

    void init() {
        pinMode(pin, INPUT);
    }

    Potentiometer(char *num, int pin) : Sensor(num) {
        this->pin = pin;
        init();
    }

    /**
     * reads potentiometer value
     */
    int read() {
        return analogRead(pin);
    }

    void consult(char *val) {
        itoc(val, this->read(), 4);
    }
};

/**
 * Frame represents a Frame for communication with the passerelle
 */
class Frame {
public:

    /* The actual data, with all possible data types inside */
    char fra;
    char *obj;
    char req;
    char typ;
    char *num;
    char *val;
    char *tim;
    char *chk;
    char *ans;
    char nbr; /* Number of data bytes */
    char *dat; /* DATA IN BINARY */

    /* field sizes */
    static const int FRA_SIZE = 1;
    static const int OBJ_SIZE = 4;
    static const int REQ_SIZE = 1;
    static const int TYP_SIZE = 1;
    static const int NUM_SIZE = 2;
    static const int VAL_SIZE = 4;
    static const int TIM_SIZE = 4;
    static const int CHK_SIZE = 2;

    /* pre-defined values */
    static const char FRA_VALUE_COMMON = 0x31;
    static const char FRA_VALUE_SYNCHRO = 0x32;
    static const char FRA_VALUE_QUICK = 0x33;

    static const char REQ_VALUE_WRITE = 0x31;
    static const char REQ_VALUE_READ = 0x32;
    static const char REQ_VALUE_READWRITE = 0x33;

    static const char TYP_VALUE_SENSOR_DISTANCE_1 = 0x31;
    static const char TYP_VALUE_SENSOR_DISTANCE_2 = 0x32;
    static const char TYP_VALUE_SENSOR_THERMOMETER = 0x33;
    static const char TYP_VALUE_SENSOR_HUMIDITY = 0x34;
    static const char TYP_VALUE_SENSOR_LIGHT_1 = 0x35;
    static const char TYP_VALUE_SENSOR_COLOR = 0x36;
    static const char TYP_VALUE_SENSOR_PRESENCE = 0x37;
    static const char TYP_VALUE_SENSOR_LIGHT_2 = 0x38;
    static const char TYP_VALUE_SENSOR_MOVEMENT = 0x39;
    static const char TYP_VALUE_SENSOR_SOUND_PRESENCE_1 = 0x41;
    static const char TYP_VALUE_DATE_DDMM = 0x42;
    static const char TYP_VALUE_DATE_YYYY = 0x43;
    static const char TYP_VALUE_ACTION_1 = 0x61;
    static const char TYP_VALUE_REQUEST_TIME_HHMM = 0x48;
    static const char TYP_VALUE_REQUEST_TIME_MMSS = 0x68;
    static const char TYP_VALUE_REQUEST_DATE_DDMM = 0x70;
    static const char TYP_VALUE_REQUEST_DATE_YYYY = 0x71;

    const char *VAL_ACTION_ORDERED = "ACTI";

    /**
     * Marshals a Frame to a char array
     *
     * @param dst
     */
    virtual void marshal(char *dst) = 0;

    /**
     * Unmarshals a Frame from a char array
     *
     * @param data
     * @return boolean, false if incorrect
     */
    virtual boolean unmarshal(char *data) = 0;

    /**
     * Calculates the size of the marshalled frame
     *
     * @return
     */
    virtual int size() = 0;

protected:

    /**
     * Writes a checksum to the given byte array
     *
     * @param dst the destination byte array, it should be at least 2 byte long
     * @param payload the payload from which to calculate checksum
     * @param payloadSize the length of the aforementioned payload
     */
    static void writeChecksum(char *dst, char *payload, size_t payloadSize) {
        int val = calcChecksum(payload, payloadSize);

        // Convert int to char*
        itoc(dst, val, CHK_SIZE);
    }

    /**
     * Validates a given checksum given the payload
     *
     * @param checksum the byte array countaining the checksum
     * @param payload the payload to be validated
     * @param payloadSize the size of the payload
     * @return
     */
    static boolean validateChecksum(char *checksum, char *payload, size_t payloadSize) {
        int expected = calcChecksum(payload, payloadSize);
        int actual = ctoi(checksum, CHK_SIZE);
        return expected == actual;
    }

    /**
     * Calculates the correct checksum given the payload
     *
     * @param payload
     * @param size
     * @return the checksum as an int
     */
    static int calcChecksum(char *payload, size_t size) {
        int value = 0;
        for (int i = 0; i < size; i++) {
            value += payload[i];
        }
        value = value % 256;
        return value;
    }
};


class CommonFrameRequest : public Frame {
public:
    static const int SIZE =
            FRA_SIZE + OBJ_SIZE + REQ_SIZE + TYP_SIZE + NUM_SIZE + VAL_SIZE + TIM_SIZE + CHK_SIZE; // = 19

    CommonFrameRequest() {
        fra = FRA_VALUE_COMMON;
        req = REQ_VALUE_WRITE;
    }

    int size() {
        return CommonFrameRequest::SIZE;
    }

    void marshal(char *dst) {
        // FRA
        dst[0] = fra;

        // OBJ
        memcpy(&dst[1], this->obj, OBJ_SIZE);

        // REQ
        dst[5] = req;

        // TYP
        dst[6] = this->typ;

        // NUM
        memcpy(&dst[7], this->num, NUM_SIZE);

        // VAL
        memcpy(&dst[9], this->val, VAL_SIZE);

        // TIM
        memcpy(&dst[13], this->tim, TIM_SIZE);

        // CHK
        //writeChecksum(&dst[17], dst, COMMON_FRAME_REQUEST_SIZE - CHK_SIZE);
        dst[17] = '0';
        dst[18] = '0';
    };

    boolean unmarshal(char *data) {
        // OBJ
        memcpy(this->obj, &data[1], OBJ_SIZE);

        // REQ
        this->req = data[5];

        // TYP
        this->typ = data[6];

        // NUM
        memcpy(this->num, &data[7], NUM_SIZE);

        // VAL
        memcpy(this->val, &data[9], VAL_SIZE);

        // TIM
        memcpy(this->tim, &data[13], TIM_SIZE);

        // CHK
        return validateChecksum(data, &data[17], SIZE - CHK_SIZE);
    };
};

class Passerelle {
public:
    HardwareSerial from = Serial;
    HardwareSerial to = Serial;
    HardwareSerial cow = Serial;

    Passerelle(HardwareSerial from, HardwareSerial to, HardwareSerial cow = Serial) {
        this->setIO(from, to);
        this->setCoW(cow);
    }

    void setIO(HardwareSerial from, HardwareSerial to) {
        this->from = from;
        this->to = to;
    }

    /**
     * Set Copy On Write stream (TODO: FIX)
     */
    void setCoW(HardwareSerial cow) {
        this->cow = cow;
    }

    // Send next frame
    void send(Frame *request, Frame *response) {
        char *buf = new char(
                request->size() > response->size() ? request->size()
                                                   : response->size()); // Give it the largest buffer needed

        this->send(request, response, buf);

        free(buf);
    }

    void send(Frame *request, Frame *response, char *buf) {
        // First marshal the request
        request->marshal(buf);

        // Write it to the output stream
        to.write((const uint8_t *) buf, request->size());

        // If copy on write, write it there to
        // TODO: make it work
        //if (cow != NULL) cow.write((const uint8_t*) buf,fra->size());;

        // Get next frame
        from.readBytes(buf, response->size());

        // Unmarshal it
        response->unmarshal(buf);
    }
};


/* CURRENT PIN CONFIGURATION */

const int DEFAULT_RGB_LEDS_PINS[3] = {30, 39, 40}; // CONNECTOR: N/A [A RW]
const int DEFAULT_BARGRAPH_LEDS_PINS[5] = {2, 34, 35, 36, 37}; // CONNECTOR: N/A
const int DEFAULT_FAN_PIN = 27; // CONNECTOR: 10
const int DEFAULT_CW_PIN = 11; // CONNECTOR: 21
const int DEFAULT_CC_PIN = 13; // CONNECTOR: 17
const int DEFAULT_POTENTIOMETER_PIN = 28; // CONNECTOR: N/A
const int DEFAULT_LIGHT_DETECTOR_PIN = 0; // TODO: FIX
const int DEFAULT_JACK_PIN = 23; // CONNECTOR: N/A
const int DEFAULT_SERVO_PIN = 39; // CONNECTOR: N/A 
const int DEFAULT_PROXIMITY_SENSOR_PIN = 25; // CONNECTOR: 7 [A RW]

/* SENSOR/ACTUATOR NUM MAPPING */
const char *BARGRAPH_NUM = "";
const char *FAN_NUM = "11";
const char *MOTOR_NUM = "10";
const char *POTENTIOMETER_NUM = "9";
const char *LIGHT_DETECTOR_NUM = "8";

/* CONTROLLERS FOR EACH SENSOR/ACTUATOR */
BarGraph bargraph = BarGraph((char *) BARGRAPH_NUM, (int *) DEFAULT_BARGRAPH_LEDS_PINS);
Fan fan = Fan((char *) FAN_NUM, DEFAULT_FAN_PIN);
Motor motor = Motor((char *) MOTOR_NUM, DEFAULT_CW_PIN, DEFAULT_CC_PIN);
Potentiometer potentiometer = Potentiometer((char *) POTENTIOMETER_NUM, DEFAULT_POTENTIOMETER_PIN);
LightDetector lightDetector = LightDetector((char *) LIGHT_DETECTOR_NUM, DEFAULT_LIGHT_DETECTOR_PIN);

/* PASSERELLE CONTROLLER */
Passerelle pass = Passerelle(Serial1, Serial1, Serial);

void setup() {
    // Init serial communication
    Serial1.begin(9600);
    Serial.begin(9600);
}

void loop() {
    /* FAN TESTING */
    fan.start();
    delay(500);
    fan.stop();
    delay(1000);

    /* FRAME SENDING/RECEIVING */
    CommonFrameRequest *frame = new CommonFrameRequest();
    frame->obj = "3C3C";
    frame->typ = 0x33;
    frame->num = "01";
    frame->val = "002B";
    frame->tim = "0125";
    pass.send(frame, new CommonFrameRequest());

    /* BARGRAPH DELAY TIMER */
    int delayTime = 5000;
    for (int i = 0; i < 5; i++) {
        bargraph.set(i, true);
        bargraph.push();
        delay(delayTime / 5);
    }
    bargraph.reset();
    bargraph.push();
}




