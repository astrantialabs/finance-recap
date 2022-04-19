import random
from pymongo import MongoClient
from random_data_dependency import *
from dotenv import dotenv_values

class Database():
    def get_cluster(mongoDBURI):
        cluster = MongoClient(mongoDBURI)
        return cluster


    def get_database(mongoDBURI, database_name):
        db = Database.get_cluster(mongoDBURI)[database_name]
        return db

    
    def get_collection(mongoDBURI, database_name, collection_name):
        collection = Database.get_database(mongoDBURI, database_name)[collection_name]
        return collection


class Main():
    def main():
        division_array = []
        division_length = random.randrange(4, 9)
        for i in range(division_length):

            activity_array = []
            division_length = random.randrange(5, 16)
            for j in range(division_length):

                detail_array = []
                detail_length = random.randrange(3, 6)
                for k in range(detail_length):
                    
                    expenses_array = []
                    expenses_length = random.randrange(3, 11)
                    for l in range(expenses_length):
                        expenses_physical_monthly_array = [[random.randrange(0, 2), random.randrange(0, 2)] for index in range(12)]
                        expenses_finance_monthly_array = [[random.randrange(100000, 2000000), random.randrange(0, 2)] for index in range(12)]

                        temp_expenses_dictionary = {
                            "id": l+1,
                            "name": random.choice(expenses_name),
                            "physical": {
                                "total": "12 Bulan",
                                "monthly": expenses_physical_monthly_array
                            },
                            "finance": {
                                "total": "12 Bulan",
                                "monthly": expenses_finance_monthly_array
                            }
                        }
                        
                        expenses_array.append(temp_expenses_dictionary)


                    monthly_finance_array = []
                    for l in range(12):
                        monthly_finance_array.append(random.randrange(100000, 2000000))

                    temp_detail_dictionary = {
                        "id": k+1,
                        "account": random.choice(account_name),
                        "total_finance": random.randrange(10000000, 30000000),
                        "monthly_finance": monthly_finance_array,
                        "expenses": expenses_array
                    }

                    detail_array.append(temp_detail_dictionary)

                
                temp_activity_dictionary = {
                    "id": j+1,
                    "activity": random.choice(activity_name),
                    "physical": random.randrange(10, 20),
                    "finance": random.randrange(10, 20),
                    "detail": detail_array
                }

                activity_array.append(temp_activity_dictionary)


            temp_division_dictionary = {
                "id": i+1,
                "name": random.choice(division_name),
                "activity": activity_array
            }

            division_array.append(temp_division_dictionary)


        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI")
        database_name = "DisnakerFinanceRecap"
        collection_name = "random_recaps"

        collection = Database.get_collection(mongoDBURI, database_name, collection_name)

        collection.insert_many(division_array)


Main.main()