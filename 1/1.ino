#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

#define MQ_PIN A0 // Pin analog untuk sensor MQ-5
#define BUZZER_PIN D3 // Pin digital untuk buzzer
#define LED_PIN D4 // Pin digital untuk LED

WiFiClient wifiClient;

const char *ssid = "hahahaha";
const char *password = "hahahaha";

void setup()
{
  Serial.begin(115200);
  pinMode(MQ_PIN, INPUT); // Mengatur pin sensor MQ-5 sebagai input
  pinMode(BUZZER_PIN, OUTPUT); // Mengatur pin buzzer sebagai output
  pinMode(LED_PIN, OUTPUT); // Mengatur pin LED sebagai output

  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED)
  { // Menunggu sampai terhubung
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println(WiFi.localIP());
}

void loop()
{
  float mq5Value = analogRead(MQ_PIN); // Membaca nilai sensor MQ-5
  int buzzerValue = analogRead(BUZZER_PIN);

  if (WiFi.status() == WL_CONNECTED)
  {
    HTTPClient http;
    Serial.print("[HTTP] begin...\n");
    String link;
    link = F("http://192.168.43.24/IoT/kirimdata.php?tingkatKebocoran=");
    link += String(mq5Value, 6);

    // Add buzzer data to the link
    link += F("&buzzer=");
    link += String(buzzerValue);

    Serial.printf("Link: %s\n", link);
    http.begin(wifiClient, link);

    Serial.print("[HTTP] GET...\n");
    int httpCode = http.GET();

    if (httpCode > 0)
    {
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);

      if (httpCode == HTTP_CODE_OK)
      {
        Serial.print(F("Berhasil mengirimkan data ke Server\n"));
        Serial.print(F("Nilai MQ-5: "));
        Serial.print(mq5Value);
        Serial.print(F(" Buzzer Value: "));
        Serial.print(buzzerValue);
      }
      else
      {
        Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
      }
      delay(50);
    }
    http.end();

    // Mengecek kondisi nilai sensor gas (MQ-5)
    if (mq5Value > 1000)
    {
      digitalWrite(BUZZER_PIN, HIGH); // Menghidupkan buzzer
      digitalWrite(LED_PIN, HIGH); // Menghidupkan LED
    }
    else
    {
      digitalWrite(BUZZER_PIN, LOW); // Mematikan buzzer
      digitalWrite(LED_PIN, LOW); // Mematikan LED
    }
  }
  else
  {
    Serial.println("Delay...");
  }
  delay(5000);
}