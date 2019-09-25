$(".delete").click(function () {
    DeleteHandler(this);
});

$(".add").click(function () {
    $("table").append($("<tr />")
        .append($("<td />").append($("<select />")))
        .append($("<td />"))
        .append($("<td />"))
        .append($("<td class='options' />")
            .append($("<button class='table save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this);
            }))
            .append($("<button class='table cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );
    let select = $("table").children().children(":last").children(":eq(0)").children("select");
    $.each(EmpDict, function (val, employee) {
        select.append($("<option value='"+val+"'>"+employee[0]+"</option>"))
    });

    select.on("change", function () {
        $(this).parent().next().text(EmpDict[$(this).val()][1]);
    });
    select.change();
});

function SaveHandler(button) {
    let row = $(button).parent().parent();
    let Employee_ID = row.children(":eq(0)").children("select").val();

    let query = "INSERT INTO RST_Employee_Task VALUES ("+Employee_ID+", "+Task_ID+")";


    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:query
        },
        beforeSend:function(){
            let mark = 0;
            $.each($("td:first-child"), function (num, td) {
                if ($(td).text() === EmpDict[Employee_ID][0]) {
                    alert("Этот исполнитель уже назначен!");
                    mark = 1;
                    return false;
                }
            });
            if(mark === 1)
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
                row.attr("id", "row-"+Employee_ID);

                row.html("")
                    .append($("<td />").text(EmpDict[Employee_ID][0]))
                    .append($("<td />").text(EmpDict[Employee_ID][1]))
                    .append($("<td />")
                        .append($("<button class='table' onclick=\"window.location.href = '/task-of-employer.php?Employee_ID="+Employee_ID+"&Fullname="+EmpDict[Employee_ID][0]+"'\">Задачи</button>"))
                    )
                    .append($("<td class='options' />")
                        .append($("<button class='table delete'>Снять с задачи</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }
        }
    });
}


function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Employee_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:"DELETE FROM RST_Employee_Task WHERE Employee_ID = " + Employee_ID + " AND Task_ID = " + Task_ID
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