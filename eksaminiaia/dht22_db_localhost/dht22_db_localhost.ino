#include <Ethernet2.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
#include "DHT.h"

#define DHTPIN 2     // what digital pin we're connected to
#define DHTTYPE DHT22   // DHT 22  (AM2302), AM2321

unsigned long currentMillis, dbUpdateTime;

int connection;

bool update1, updatedb = false;

byte mac_addr[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };

IPAddress server_addr(192, 168, 1, 100); // IP of the MySQL *server* here

char user[] = "arduino";              // MySQL user login username
char password[] = "raspi0196";        // MySQL user login password

// Sample query
char INSERT_DATA[] = "INSERT INTO dht.dht11 (humidity, temperature) VALUES (%s,%s)";

char query[128];
char temperature[10];
char humidity[10];


EthernetClient client;
MySQL_Connection conn((Client *)&client);


DHT dht(DHTPIN, DHTTYPE);

void setup() {

  Serial.begin(115200);
  while (!Serial); // wait for serial port to connect
  Ethernet.begin(mac_addr);
  dht.begin();
 

  Serial.println("Connecting...");
  if (conn.connect(server_addr, 3306, user, password)) {
    delay(1000);
    Serial.println("Connected");
    connection = 1;
  }
  else
    Serial.println("Connection failed.");
  connection = 0;
}

void loop() {

  currentMillis = millis();

  float h = dht.readHumidity();
  // Read temperature as Celsius (the default)
  float temp = dht.readTemperature();


  if (connection = 1 && updatedb == false) {
    Serial.println("START");
    dbUpdateTime = currentMillis;
    updatedb = true;
  }

  if (update1 == false) {
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    dtostrf(temp, 1, 1, temperature);
    dtostrf(h, 1, 1, humidity);
    sprintf(query, INSERT_DATA, humidity, temperature);
    // Execute the query
    cur_mem->execute(query);
    // Note: since there are no results, we do not need to read any data
    // Deleting the cursor also frees up memory used
    delete cur_mem;
    Serial.println("Db Updated.");
    update1 = true;
  }

  if (currentMillis - dbUpdateTime >= 300000) {
    // Initiate the query class instance
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    dtostrf(temp, 1, 1, temperature);
    dtostrf(h, 1, 1, humidity);
    sprintf(query, INSERT_DATA, humidity, temperature);
    // Execute the query
    cur_mem->execute(query);
    // Note: since there are no results, we do not need to read any data
    // Deleting the cursor also frees up memory used
    delete cur_mem;
    Serial.println("Db Updated.");
    dbUpdateTime = currentMillis;
    updatedb = false;
  }

  Serial.print("Temperature:");   Serial.print(temp);  Serial.print(" ");
  Serial.print("Humidity :");   Serial.println(h);  Serial.print(" ");


  delay(200);

}
