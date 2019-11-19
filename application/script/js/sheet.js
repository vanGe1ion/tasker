//meta
var currentSheet = null;
var currentSheetList = {};



//управление датой
$("#prevDate").click(function () {
    DateWalker(-1);
});

$("#nextDate").click(function () {
    DateWalker(1);
});

function DatePickerHandler(event){
    let promise = null;
    currentSheet = null;
    let currentDate = $(event.currentTarget).val();

    let dayCheck = (new Date(currentDate)).getDay();
    switch (dayCheck) {
        case 0:{
            DateWalker(-2);
            break;
        }
        case 6:{
            DateWalker(+2);
            break;
        }
        default: {
            promise = CurrentSheetIDPromise(currentDate).done(function (sheetID) {
                if (sheetID) {
                    currentSheet = sheetID;
                    promise = SheetElemListPromise({Sheet_ID: sheetID}).done(SheetTableCreator)
                } else
                    SheetTableCreator({});
            });
            promise.fail(AjaxFailHandler);
        }
    }
}

function DateWalker(dayWalk){
    let date = $("#sheetDate").val() ? new Date($("#sheetDate").val()) : new Date();
    date.setDate(date.getDate() + dayWalk);
    $("#sheetDate").val(date.toISOString().split("T")[0]).change();
}



//словари
var EmployeeDictionary = $.ajax({
    url:"/application/script/php/ajaxer.php",
    type:"post",
    dataType:"JSON",
    data: {
        querySet: "EmployeeQuerySet",
        queryType: "read",
        queryData: null
    }
})
    .pipe(function (res) {
        if(res != false) {
            let ret = {};
            $.each(res, function (num, field) {
                let initials = "";
                let split = field.Fullname.split(" ");
                if (split.length == 3)
                    initials = split[0] + " " + split[1][0] + "." + split[2][0] + ".";
                else
                    initials = split[0] + (split[1] != null ? (" " + split[1]) : "");

                ret[field.Employee_ID] = {
                    Initials: initials,
                    Position: field.Position
                }
            });
            return ret;
        }
    });

var StateDictionary = $.ajax({
    url:"/application/script/php/ajaxer.php",
    type:"post",
    dataType:"JSON",
    data: {
        querySet: "StateQuerySet",
        queryType: "read",
        queryData: null
    }
})
    .pipe(function (res) {
        if(res != false) {
            let ret = {};
            $.each(res, function (num, field) {
                ret[field.State_ID] = {
                    State_Name: field.State_Name,
                    Description: field.Description
                }
            });
            return ret;
        }
    });

var AdminFlag = $.ajax({
    url:"/application/script/php/a_verify.php",
    type:"post",
    dataType:"JSON",
    data: {}
});

//init
$.when(EmployeeDictionary, StateDictionary, AdminFlag)
    .done(function () {
        $("#sheetDate").change(DatePickerHandler);
        DateWalker(0);

        //подготовка модального окна
        StateDictionary.done(function (stateList) {
            let select = $("#durableState");
            select.append($("<option />"));
            $.each(stateList, function (stateId, state) {
                let optionLabel = (state.State_Name.length == 1 ? "- " : "") + state.State_Name + " - " + state.Description;
                select.append($("<option value ='" + +stateId + "'>" + optionLabel + "</option>"));
            });
            select.change(function () {
                if($(this).val())
                    $("#durableComment").removeAttr("disabled");
                else
                    $("#durableComment").val("").attr("disabled", "");
            })
        });
    })
    .fail(function () {
        alert("Ошибка загрузки словарей. Перезагрузите страницу");
    });



//AJAX
function CurrentSheetIDPromise(date){
    return $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        dataType:"JSON",
        data: {
            querySet: "SheetQuerySet",
            queryType: "read",
            queryData: {
                Date: date
            }
        }
    })
        .pipe(function (res) {
            return res[0] != null ? res[0].Sheet_ID : null;
        });
}

function NewSheetIDPromise(){
    return $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        dataType:"JSON",
        data: {
            querySet: "SheetQuerySet",
            queryType: "max",
            queryData: null
        }
    })
        .pipe(function (res) {
            return res[0] != null ? (Number((res[0]).Sheet_ID) + 1) : 1;
        });
}

function SheetOperationPromise(queryData, type) {
    return $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        dataType:"JSON",
        data: {
            querySet: "SheetQuerySet",
            queryType: type,
            queryData: queryData
        }
    })
}

function SheetElemListPromise(queryData){
    return $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        dataType:"JSON",
        data: {
            querySet: "RSTEmployeeSheetQuerySet",
            queryType: "read",
            queryData: queryData
        }
    })
        .pipe(function (res) {
            let ret = {};
            $.each(res, function (num, field) {
                ret[field.Employee_ID] = {
                    State_ID: field.State_ID,
                    State_Name: field.State_Name,
                    Comment: field.Comment
                }
            });
            return ret;
        });
}

function SheetElemOperationPromise(queryData, type) {
    return $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        dataType:"JSON",
        data: {
            querySet: "RSTEmployeeSheetQuerySet",
            queryType: type,
            queryData: queryData
        }
    })
}



//работа с таблицами
function SheetTableCreator(sheetList){
    $("#sheetData, #durables").html("");
    currentSheetList = sheetList;
    EmployeeDictionary.done(function (employeeList) {
        $.each(employeeList, function (empId, employee) {
            let newRow = $("<tr id='employee-" + empId + "'/>")
                .append($("<td>" + empId + "</td>"))
                .append($("<td>" + employee.Position + "</td>"))
                .append($("<td>" + employee.Initials + "</td>"));

            AdminFlag.done(function (isAdmin) {
                if(isAdmin){
                    newRow.append($("<td class='d-flex flex-row'/>")
                        .append($("<select class='form-control form-control-sm mr-2 state'/>"))
                        .append($("<button title='Установить диапазон' class='btn btn-sm btn-primary' data-toggle='modal'" +
                            " data-target='#modalDurable' data-employee='" + empId + "'>< ... - ... ></button>"))
                    );
                    newRow.append($("<td />").append($("<input type='text' disabled class='form-control form-control-sm comment'/>")));


                    StateDictionary.done(function (stateList) {
                        let select = newRow.children().children(".state");
                        select.append($("<option />"));
                        $.each(stateList, function (stateId, state) {
                            let optionLabel = (state.State_Name.length == 1 ? "- " : "") + state.State_Name + " - " + state.Description;
                            let selected = (sheetList[empId] && +sheetList[empId].State_ID == stateId ? "selected" : "");
                            if (selected != "")
                                newRow.children().children(".comment").removeAttr("disabled");
                            select.append($("<option " + selected + " value ='" + +stateId + "'>" + optionLabel + "</option>"));
                        });
                        select.change(SheetElemHandler);
                    });


                    let comment = newRow.children().children(".comment");
                    comment.val(sheetList[empId] ? sheetList[empId].Comment : "");
                    comment.keypress(function(event){
                        if(event.keyCode == 13)
                            $(this).blur();
                    });
                    comment.focusout(SheetElemHandler);
                }
                else{
                    newRow.append($("<td>" + (sheetList[empId] ? sheetList[empId].State_Name : "") + "</td>"));
                    newRow.append($("<td>" + (sheetList[empId] ? sheetList[empId].Comment : "") + "</td>"));
                }
            });

            $("#sheetData").append(newRow);
        });
        DurableSorter();
    })
}

function SheetElemHandler(event){
    let promise = null;
    let currentRow = $(event.currentTarget).parent().parent();
    let data = {
        Sheet_ID: currentSheet,
        Employee_ID: +(currentRow.attr("id").split("-")[1]),
        State_ID: +currentRow.children().children(".state").val(),
        Comment: currentRow.children().children(".comment").val()
    };

    if (data.Sheet_ID)
        SheetElemOperator(data, currentRow);
    else{
        promise = NewSheetIDPromise().done(function (newSheet) {
            promise = SheetOperationPromise({Sheet_ID: newSheet, Date: $("#sheetDate").val()}, "create").done(function (res) {
                OperationHandler(res, function () {
                    currentSheet = newSheet;
                    data.Sheet_ID = newSheet;
                    SheetElemOperator(data, currentRow);
                })
            })
        });
        promise.fail(AjaxFailHandler);
    }
}

function SheetElemOperator(queryData, currentRow){
    let currSheetElem = currentSheetList[queryData.Employee_ID];

    if(!currSheetElem && queryData.State_ID)
            OperationExecutor("create", queryData, function () {
                currentRow.children().children(".comment").removeAttr("disabled");
                currentSheetList[queryData.Employee_ID] = {State_ID: queryData.State_ID, Comment: queryData.Comment}
            });
    else if(currSheetElem)
        if (queryData.State_ID)
            OperationExecutor("update", queryData, function () {
                currentSheetList[queryData.Employee_ID] = {State_ID: queryData.State_ID, Comment: queryData.Comment}
            });
        else
            OperationExecutor("delete", queryData, function () {
                currentRow.children().children(".comment").attr("disabled", "disabled").val("");
                delete (currentSheetList[queryData.Employee_ID]);
                if (!Object.keys(currentSheetList).length)
                    SheetOperationPromise({Sheet_ID: currentSheet}, "delete")
                        .done(function () {
                            currentSheet = null;
                        })
                        .fail(AjaxFailHandler);
            });
}



//обработка операций
function OperationExecutor(type, queryData, success) {
    SheetElemOperationPromise(queryData, type).done(function (res) {
        OperationHandler(res, success)
    })
        .fail(AjaxFailHandler);
}

function OperationHandler(operationResult, success){
    if(operationResult) {
        success();
        DurableSorter();
    }
    else
        SqlFailHandler();
}



//Обработка ошибок
function AjaxFailHandler(){
    alert("Ошибка AJAX");
}

function SqlFailHandler(){
    alert("Запрос не выполнен");
}



//работа с длительными состояниями
$('#modalDurable').on('show.bs.modal', function (event) {
    let employee = +$(event.relatedTarget).data('employee');
    $(this).find('#empName').text($("#employee-" + employee + " > td:nth-child(3)").text());
    $("#durableFrom").val($("#sheetDate").val());
    $("#durableSave").data("employee", employee);
});

$("#durableSave").click(function () {
    $("#durableFrom, #durableTo").removeClass("is-invalid");
    let dateFrom = $("#durableFrom").val();
    let dateTo = $("#durableTo").val();
    if(dateFrom === "")
        $("#durableFrom").addClass("is-invalid");
    if(dateTo === "")
        $("#durableTo").addClass("is-invalid");


    if(dateFrom && dateTo){
        $("#durableCancel, #durableSave").attr("disabled", "");
        $(".spinner-border").removeClass("d-none");

        $(document).on("ajaxStop", function () {
            $("#durableCancel, #durableSave").removeAttr("disabled");
            $(".spinner-border").addClass("d-none");
            $("#durableCancel").click();
            DateWalker(0);
            $(this).off("ajaxStop");
        });

        let start = new Date(dateFrom);
        let end = new Date(dateTo);
        let empID = $(this).data("employee");
        for(let iterDate = start; iterDate <= end; iterDate.setDate(iterDate.getDate() + 1))
            if (iterDate.getDay() != 0 && iterDate.getDay() != 6) {
                DurableStateOperator(empID, iterDate);
            }
    }
});

function DurableStateOperator(empID, iterDate) {
    let date = iterDate.toISOString().split("T")[0];
    let queryData = {
        Employee_ID: empID,
        State_ID: +$("#durableState").val(),
        Comment: $("#durableComment").val()
    };

    let promise = CurrentSheetIDPromise(date).done(function (sheetId) {
        if(sheetId){
            queryData.Sheet_ID = +sheetId;
            promise = SheetElemListPromise({Sheet_ID: sheetId}).done(function (res) {
                let elemList = res;
                if(elemList[empID])
                    if(queryData.State_ID)
                        OperationExecutor("update", queryData, function () {});
                    else
                        OperationExecutor("delete", queryData, function () {
                            delete (elemList[empID]);
                            if (!Object.keys(elemList).length)
                                promise = SheetOperationPromise({Sheet_ID: sheetId}, "delete")
                        });
                else
                    OperationExecutor("create", queryData, function () {});
            })
        }
        else
            if (queryData.State_ID)
                promise = SheetOperationPromise({Date: date}, "create").done(function (opResult) {
                    OperationHandler(opResult, function () {
                        promise = CurrentSheetIDPromise(date).done(function (sheetId) {
                            queryData.Sheet_ID = +sheetId;
                            OperationExecutor("create", queryData, function () {
                            });
                        });
                    });
                });
    });

    promise.fail(AjaxFailHandler);
}

function DurableSorter() {
    let mask = {
        9: "ОТ",
        10: "ОД",
        15: "ОЖ",
        19: "Б"
    };

    AdminFlag.done(function (isAdmin) {
        $.each($("#sheetData tr"), function (key, row) {
            if(
                isAdmin && mask[$(row).find(".state").val()] ||
                !isAdmin && Object.values(mask).includes($(row).children(":eq(3)").text())
            )
                $(row).addClass("table-danger");
        });
        $("#durables").append($("#sheetData .table-danger").detach());


        $.each($("#durables tr"), function (key, row) {
            if(
                isAdmin && !mask[$(row).find(".state").val()] ||
                !isAdmin && !Object.values(mask).includes($(row).children(":eq(3)").text())
            )
                $(row).removeAttr("class");
        });
        $("#sheetData").append($("#durables tr:not([class])").detach());
    });
}