function SelCity(a, t) {
    var s = a;
    Iput.show({
        id: s,
        event: t,
        content: '<div class="_citys"><span title="关闭" id="cColse" >×</span><ul id="_citysheng" class="_citys0"><li class="citySel">省份</li><li>城市</li><li>区县</li></ul><div id="_citys0" class="_citys1"></div><div style="display:none" id="_citys1" class="_citys1"></div><div style="display:none" id="_citys2" class="_citys1"></div></div>',
        width: "470"
    }), $("#cColse").click(function () {
        Iput.colse()
    });
    for (var i = [], e = province, d = 0, l = e.length; d < l; d++) i.push('<a data-level="0" data-id="' + e[d].id + '" data-name="' + e[d].name + '">' + e[d].name + "</a>");
    $("#_citys0").append(i.join("")), $("#_citys0 a").click(function () {
        var a = getCity($(this));
        $("#_citys1 a").remove(), $("#_citys1").append(a), $("._citys1").hide(), $("._citys1:eq(1)").show(), $("#_citys0 a,#_citys1 a,#_citys2 a").removeClass("AreaS"), $(this).addClass("AreaS");
        var t = $(this).data("name");
        if (s.value = $(this).data("name"), null == document.getElementById("hcity")) {
            var i = $("<input>", {type: "hidden", name: "hcity", "data-id": $(this).data("id"), id: "hcity", val: t});
            $(s).after(i)
        } else $("#hcity").val(t), $("#hcity").attr("data-id", $(this).data("id"));
        $("#_citys1 a").click(function () {
            $("#_citys1 a,#_citys2 a").removeClass("AreaS"), $(this).addClass("AreaS");
            var a = $(this).data("name");
            if (null == document.getElementById("hproper")) {
                var t = $("<input>", {
                    type: "hidden",
                    name: "hproper",
                    "data-id": $(this).data("id"),
                    id: "hproper",
                    val: a
                });
                $(s).after(t)
            } else $("#hproper").attr("data-id", $(this).data("id")), $("#hproper").val(a);
            var i = $("#hcity").val();
            s.value = i + "-" + $(this).data("name");
            var e = getArea($(this));
            $("#_citys2 a").remove(), $("#_citys2").append(e), $("._citys1").hide(), $("._citys1:eq(2)").show(), $("#_citys2 a").click(function () {
                $("#_citys2 a").removeClass("AreaS"), $(this).addClass("AreaS");
                var a = $(this).data("name");
                if (null == document.getElementById("harea")) {
                    var t = $("<input>", {
                        type: "hidden",
                        name: "harea",
                        "data-id": $(this).data("site_code"),
                        id: "harea",
                        val: a
                    });
                    $(s).after(t)
                } else $("#harea").val(a), $("#harea").attr("data-id", $(this).data("site_code"));
                $("#hcity").val(), $("#hproper").val();
                var i = $(this).data("id");
                sub_returl = sub_returl.replace(".html", ""), window.location.href = sub_returl + "/" + i + ".html", Iput.colse()
            })
        })
    }), $("#_citysheng li").click(function () {
        $("#_citysheng li").removeClass("citySel"), $(this).addClass("citySel");
        var a = $("#_citysheng li").index(this);
        $("._citys1").hide(), $("._citys1:eq(" + a + ")").show()
    })
}

function getCity(a) {
    for (var t, i = a.data("id"), e = province, s = "", d = 0, l = e.length; d < l; d++) if (e[d].id == parseInt(i)) {
        t = e[d].city;
        break
    }
    for (var c = 0, n = t.length; c < n; c++) s += '<a data-level="1" data-id="' + t[c].id + '" data-name="' + t[c].name + '" title="' + t[c].name + '">' + t[c].name + "</a>";
    return $("#_citysheng li").removeClass("citySel"), $("#_citysheng li:eq(1)").addClass("citySel"), s
}

function getArea(a) {
    for (var t = a.data("id"), i = area, e = [], s = "", d = 0, l = i.length; d < l; d++) i[d].pid == parseInt(t) && e.push(i[d]);
    for (var c = 0, n = e.length; c < n; c++) s += '<a data-level="1" data-id="' + e[c].site_code + '" data-name="' + e[c].name + '" title="' + e[c].name + '">' + e[c].name + "</a>";
    return $("#_citysheng li").removeClass("citySel"), $("#_citysheng li:eq(2)").addClass("citySel"), s
}