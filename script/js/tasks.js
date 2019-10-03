$(".edit").click(function () {
    EditHandler(this);
});

$(".delete").click(function () {
    DeleteHandler(this);
});

$("a.dropdown-item").click(function (e) {
    e.preventDefault();
    DropdownItemHandler(this);
});

$(".dismiss").click(function () {
    EmployerDismissHandler(this);
});

$(".add").click(function () {
    let lastRow = $(this).parent().parent().siblings(":last");
    let taskNum = lastRow.length? (Number(lastRow.attr("id").split("-")[1]) + 1): 1;
    $(this).parent().parent().before($("<tr id='row-"+ taskNum +"' />")
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>")))
        .append($("<td />").append($("<select class='form-control form-control-sm status' />")))
        .append($("<td class='result'/>").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td />")
            .append($("<div style='width: 180px'>")
                .append($("<div class='btn-group btn-group-sm empAdd' />")
                    .append($("<button class='btn btn-sm btn-success empButton disabled'>Назначить</button>"))
                    .append($("<button class='btn btn-sm btn-success dropdown-toggle dropdown-toggle-split empOperation disabled'  data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>"))
                    .append($("<div class='dropdown-menu dropdown-menu-right' />"))
                )
            )
        )
        .append($("<td class='options' />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "create");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );

    let select = $("#row-"+taskNum).children().children(".status");
    $.each(StatusDict, function (val, text) {
        select.append($("<option value='"+val+"'>"+text+"</option>"))
    });
    let newEmp = $("#row-"+taskNum+" .empAdd .dropdown-menu");
    $.each(EmpDict, function (key, emp) {
        newEmp.append($("<a id='newemp-"+key+"' href='#' class='dropdown-item'>"+emp+"</a>"))
    });
});

function SaveHandler(button, operation) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);
    let Description = row.children(":eq(0)").children(":text").val();
    let StartDate = row.children(":eq(1)").children("input").val();
    let EndDate = row.children(":eq(2)").children("input").val();
    let Status = +(row.children(":eq(3)").children("select").val());
    let ResultPointer = row.children(":eq(4)").children(":text").val();
    let Employees = row.children(":eq(5)").children().clone();
    Employees.children(".empAdd").children("div").children().click(function (e) {
        e.preventDefault();
        DropdownItemHandler(this);
    });

    let query = "";
    if(operation === "create")
        query = "TaskCreate";
    else
        query = "TaskEdit";

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        data:{
            queryName:query,
            data:{
                Task_ID:Task_ID,
                Description:Description,
                Start_Date:StartDate,
                End_Date:EndDate,
                Status:Status,
                Result_Pointer:ResultPointer
            }
        },
        beforeSend:function () {
            row.children().children(":text, [type='date']").removeClass("is-invalid");
            if(Description === "")
                row.children(":eq(0)").children(":text").addClass("is-invalid");
            if(StartDate === "")
                row.children(":eq(1)").children("input").addClass("is-invalid");
            // if(EndDate === "")
            //     row.children(":eq(2)").children("input").addClass("is-invalid");
            if(Description === "" || StartDate === "")// || EndDate === "")
                return false;
            else
                return true;
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                Employees.children().children().removeClass("disabled");
                row.addClass(StatusBase[StatusDict[Status]]).html("")
                    .append($("<td />").text(Description))
                    .append($("<td />").text(ToNormalDate(StartDate)))
                    .append($("<td />").text(ToNormalDate(EndDate)))
                    .append($("<td />").text(StatusDict[Status]))
                    .append($("<td class='result' />").text(ResultPointer))
                    .append($("<td />").append(Employees))
                    .append($("<td />")
                        .append($("<button class='btn btn-secondary btn-sm edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='btn btn-secondary btn-sm delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }
        }
    });
}

function EditHandler(button) {
    let row = $(button).parent().parent();
    let Description = row.children(":eq(0)").text();
    let StartDate = row.children(":eq(1)").text();
    let EndDate = row.children(":eq(2)").text();
    let Status = row.children(":eq(3)").text();
    let ResultPointer = row.children(":eq(4)").text();
    let Employees = row.children(":eq(5)").children().clone();
    Employees.children().children().addClass("disabled");

    row.html("")
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>").val(Description)))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>").val(ToUnnormalDate(StartDate))))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>").val(ToUnnormalDate(EndDate))))
        .append($("<td />").append($("<select class='status form-control form-control-sm' />")))
        .append($("<td class='result' />").append($("<input class='form-control form-control-sm' type='text'>").val(ResultPointer)))
        .append($("<td />").append(Employees))
        .append($("<td />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                Employees.children().children().removeClass("disabled");
                row.html("")
                    .append($("<td />").text(Description))
                    .append($("<td />").text(StartDate))
                    .append($("<td />").text(EndDate))
                    .append($("<td />").text(Status))
                    .append($("<td class='result'/>").text(ResultPointer))
                    .append($("<td />").append(Employees))
                    .append($("<td />")
                        .append($("<button class='btn btn-secondary btn-sm edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='btn btn-secondary btn-sm delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }))
        );

    $.each(StatusDict, function (val, text) {
        row.children().children(".status").append($("<option value='"+val+"'>"+text+"</option>"))
    });
    row.children().children(".status").children("option:contains('"+Status+"')").attr("selected", "selected");
}

function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        data:{
            queryName:"TaskRemove",
            data:{
                Task_ID:Task_ID
            }
        },
        beforeSend:function () {
            return confirm("Вы действительно хотите удалить запись?");
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else
                row.remove();
        }
    });

}

function DropdownItemHandler(item) {
    let row = $(item).parentsUntil("tbody").last();
    let taskID = row.attr("id").split("-")[1];
    let empID = $(item).attr("id").split("-")[1];

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            queryName:"RSTEmpTaskCreate",
            data:{
                Employee_ID:empID,
                Task_ID:taskID
            }
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                $(item).addClass("disabled");
                let btngp = $(item).parent().parent();
                btngp.before($("<div class='btn-group btn-group-sm mb-1' />")
                    .append($("<button class='btn btn-sm btn-primary empButton'>"+$(item).text()+"</button>"))
                    .append($("<button id='emp-"+empID+"' class='btn btn-sm btn-primary empOperation dismiss'>x</button>").click(function () {
                        EmployerDismissHandler(this);
                    }))
                )
            }
        }
    });
}

function EmployerDismissHandler(button) {
    let row = $(button).parentsUntil("tbody").last();
    let taskID = row.attr("id").split("-")[1];
    let empID = $(button).attr("id").split("-")[1];
    let fullname =  $(button).siblings().text();

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            queryName:"RSTEmpTaskRemove",
            data:{
                Employee_ID: empID,
                Task_ID:taskID
            }
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                $(button).parent().siblings(":last").children("div").children(":contains("+fullname+")").removeClass("disabled");
                $(button).parent().remove();
            }
        }
    });
}

function ToNormalDate(date) {
    if (date === "")
        return "";
    else {
        let source = new Date(date);
        let day = source.getDate();
        let month = source.getMonth() + 1;
        let year = source.getFullYear();
        return (day < 10 ? "0" : "") + day + "." + (month < 10 ? "0" : "") + month + "." + year;
    }
}

function ToUnnormalDate(date) {
    let source = date.split(".");
    let day = source[0];
    let month = source[1];
    let year = source[2];
    return year + "-" + month + "-" + day;

}