import PySimpleGUI as sg
import json

class Main():
    def main():
        data = json.load(open("./api/setting/json/setting.json")) #path

        data_count = 0
        detail_count = 0
        
        current_data = data[data_count]
        current_data_detail = current_data.get("detail")[detail_count]

        current_data_attribute = ["name", "start_range", "end_range"]
        current_data_detail_attribute = ["active_sheet", "start_range", "end_range", "attribute"]

        list_data = [
            division.get("name") for division in data
        ]

        layout = [
            [sg.Listbox(list_data, default_values=data[data_count].get("name"), key="listbox", enable_events=True, size=(100, 4), no_scrollbar=True, select_mode="single")],
            
            [sg.Text("Division")],
            [sg.InputText(default_text=current_data.get("id"), size=(100, 1), key="data_id", enable_events=True, readonly=True)],
            [[sg.InputText(default_text=current_data.get(attribute), size=(100, 1), key=f"data_{attribute}", enable_events=True)] for attribute in current_data_attribute],

            [sg.Text("Detail")],
            [sg.InputText(default_text=current_data_detail.get("id"), size=(100, 1), key="detail_id", enable_events=True, readonly=True)],
            [[sg.InputText(default_text=current_data_detail.get(attribute), size=(100, 1), key=f"detail_{attribute}", enable_events=True)] for attribute in current_data_detail_attribute],
            
            [
                sg.Button('Previous', key="previous_button", enable_events=True, disabled = True),
                sg.Button('Next', key="next_button", enable_events=True)
            ]
        ]

        window = sg.Window("Excel Attribute Editor", layout, size=(500, 500))

        while True:
            event, values = window.read()
            if(event == sg.WINDOW_CLOSED):
                break

            elif(event == "listbox"):
                pass

            elif(event == "previous_button" or event == "next_button"):
                if(event == "previous_button" and detail_count != 0):
                    detail_count -= 1

                elif(event == "next_button" and detail_count != len(data[data_count].get("detail"))-1):
                    detail_count += 1 

                if(type(data[data_count].get("detail")[detail_count]) == dict):
                    window["detail_id"].update(data[data_count].get("detail")[detail_count].get("id"))

                    for attribute in current_data_detail_attribute:
                        window[f"detail_{attribute}"].update(data[data_count].get("detail")[detail_count].get(attribute))


                elif(type(data[data_count].get("detail")[detail_count]) != dict):
                    window["detail_id"].update(detail_count + 1)

                    for attribute in current_data_detail_attribute:
                        window[f"detail_{attribute}"].update("null")
            

            if(detail_count == 0):
                window["previous_button"].update(disabled = True)

            if(detail_count != 0):
                window["previous_button"].update(disabled = False)

            if(detail_count == len(data[data_count].get("detail"))-1):
                window["next_button"].update(disabled = True)

            if(detail_count != len(data[data_count].get("detail"))-1):
                    window["next_button"].update(disabled = False)


        window.close()


if(__name__ == "__main__"):
    Main.main()