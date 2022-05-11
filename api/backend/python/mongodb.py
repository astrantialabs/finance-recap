from pymongo import MongoClient
from dotenv import dotenv_values

env_value = dotenv_values("./api/.env")

cluster = MongoClient(env_value.get("APIdbURI"))
database = cluster["Production"]
collection = database["summary_recaps"]

data = list(collection.find({}, {"_id": 0, "name": 1}))

for division in data:
    cluster.drop_database(f"Pro{division.get('name')}")
