import enum
import PySimpleGUI as sg
import json

data = json.load(open("./api/setting/json/setting.json")) #path
current_data = data[0]
current_data_detail = current_data.get("detail")[0]

current_data_attribute = ["name", "start_range", "end_range"]
current_data_detail_attribute = ["active_sheet", "start_range", "end_range", "attribute"]

detail_count = 0

list_data = [
    division.get("name") for division in data
]

layout = [
    [sg.Listbox(list_data, default_values=data[0].get("name"), key="listbox", enable_events=True, size=(100, 4), no_scrollbar=True, select_mode="single")],
    
    [sg.Text("Division")],
    [sg.InputText(default_text=current_data.get("id"), size=(100, 1), key="data_id", enable_events=True, readonly=True)],
    [[sg.InputText(default_text=current_data.get(attribute), size=(100, 1), key=f"data_{attribute}", enable_events=True)] for attribute in current_data_attribute],

    [sg.Text("Detail")],
    [sg.InputText(default_text=current_data_detail.get("id"), size=(100, 1), key="detail_id", enable_events=True, readonly=True)],
    [[sg.InputText(default_text=current_data_detail.get(attribute), size=(100, 1), key=f"detail_{attribute}", enable_events=True)] for attribute in current_data_detail_attribute],
    
    [
        sg.Button('Previous', key="previous_button", enable_events=True),
        sg.Button('Next', key="next_button", enable_events=True)
    ]
]

window = sg.Window("Excel Cell Range Setting", layout, size=(500, 500))

while True:
    event, values = window.read()
    if event == sg.WINDOW_CLOSED:
        break

    elif event == "listbox":
        pass

    elif event == "previous_button":
        name = values["data_name"]
       
        for i in range(len(data)):
            if(data[i].get("name") == name):
                detail_count -= 1

                if(type(data[i].get("detail")[detail_count]) == dict):
                    window["detail_id"].update(data[i].get("detail")[detail_count].get("id"))
                    window["detail_active_sheet"].update(data[i].get("detail")[detail_count].get("active_sheet"))
                    window["detail_start_range"].update(data[i].get("detail")[detail_count].get("start_range"))
                    window["detail_end_range"].update(data[i].get("detail")[detail_count].get("end_range"))
                    window["detail_attribute"].update(data[i].get("detail")[detail_count].get("attribute"))

                elif(type(data[i].get("detail")[detail_count]) != dict):
                    window["detail_id"].update(detail_count + 1) 
                    window["detail_active_sheet"].update("null")
                    window["detail_start_range"].update("null")
                    window["detail_end_range"].update("null")
                    window["detail_attribute"].update("null")


    elif event == "next_button":
        name = values["data_name"]
       
        for i in range(len(data)):
            if(data[i].get("name") == name):
                detail_count += 1

                if(type(data[i].get("detail")[detail_count]) == dict):
                    window["detail_id"].update(data[i].get("detail")[detail_count].get("id"))
                    window["detail_active_sheet"].update(data[i].get("detail")[detail_count].get("active_sheet"))
                    window["detail_start_range"].update(data[i].get("detail")[detail_count].get("start_range"))
                    window["detail_end_range"].update(data[i].get("detail")[detail_count].get("end_range"))
                    window["detail_attribute"].update(data[i].get("detail")[detail_count].get("attribute"))

                elif(type(data[i].get("detail")[detail_count]) != dict):
                    window["detail_id"].update(detail_count + 1) 
                    window["detail_active_sheet"].update("null")
                    window["detail_start_range"].update("null")
                    window["detail_end_range"].update("null")
                    window["detail_attribute"].update("null")


window.close()