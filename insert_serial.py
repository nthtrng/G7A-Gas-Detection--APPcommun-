import serial
import mysql.connector
import time

# Config port série
ser = serial.Serial('COM4', 9600, timeout=2)

# Config BDD
db = mysql.connector.connect(
    host="178.33.122.21",
    port=3306,
    database="hangardb_bedi64240",
    user="bedi64240",
    password="4HG6UkdXvSgabKiuOFbmU107"
)
cursor = db.cursor()

print("Lecture du port série... (Ctrl+C pour arrêter)")

while True:
    try:
        line = ser.readline().decode('utf-8').strip()
        if line.startswith("GAZ:"):
            parts = line.split(";")
            gaz = int(parts[0].replace("GAZ:", ""))
            alert = int(parts[1].replace("ALERT:", ""))

            cursor.execute(
                "INSERT INTO gas_measures_g7a (sensor_name, gas_type, gas_value, danger_level) VALUES (%s, %s, %s, %s)",
                ("MQ135", "CO2/gas", gaz, alert)
            )
            db.commit()
            print(f"Inséré : GAZ={gaz}, ALERT={alert}")

    except KeyboardInterrupt:
        print("Arrêt.")
        break
    except Exception as e:
        print(f"Erreur : {e}")
        time.sleep(1)

ser.close()
cursor.close()
db.close()