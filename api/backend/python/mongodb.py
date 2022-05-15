from pymongo import MongoClient
from dotenv import dotenv_values

class Main():
    env_value = dotenv_values("./api/.env")

    def main():
        cluster = MongoClient(Main.env_value.get("APIdbURI"))

        if(Main.env_value.get("production_status") == "Production"):
            database = cluster["Production"]

        elif(Main.env_value.get("production_status") == "Development"):
            database = cluster["Development"]
        
        # Main.drop_file_database(cluster, database)


    def drop_file_database(cluster, database):
        collection = database["summary_recaps"]

        data = list(collection.find({}, {"_id": 0, "name": 1}))

        for division in data:
            if(Main.env_value.get("production_status") == "Production"):
                cluster.drop_database(f"Pro{division.get('name')}")

            elif(Main.env_value.get("production_status") == "Development"):
                cluster.drop_database(f"Dev{division.get('name')}")
            

Main.main()