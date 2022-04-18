import json

class Main():
    def main():
        data = json.load(open("api/setting/json/untranslated_setting.json"))

        dictionary_array = []
        for i in range(len(data)):
            detail_array = []
            for j in range(len(data[i][3])):
                if(type(data[i][3][j]) == list):
                    temp_detail = {
                        "active_Sheet": data[i][3][j][0],
                        "start_range": data[i][3][j][1],
                        "end_range": data[i][3][j][2],
                        "attribute": data[i][3][j][3]
                    }

                elif(type(data[i][3][j]) != list):
                    temp_detail = None

                detail_array.append(temp_detail)


            temp_dictionary = {
                "name": data[i][2],
                "start_range": data[i][0],
                "end_range": data[i][1],
                "detail": detail_array
            }

            dictionary_array.append(temp_dictionary)


        json_object = json.dumps(dictionary_array, indent = 4)
        with open("api/setting/json/setting.json", "w") as outfile:
            outfile.write(json_object)


Main.main()