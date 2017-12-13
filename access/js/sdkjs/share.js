function yxalert(txt) {
    layer.alert(txt, {shift: 7});
}
function yxnotice(txt) {
    layer.msg(txt,
        {
            shift : 7,
            offset: '40px',
            area  : '300px'
        }
    );
}
function redirect_delay(url, time) {
    setTimeout(function () {
            location.href = url;
        },
        time);
}

function reload_delay() {
    setTimeout(function () {
        location.reload();
    }, 1000);
}

function is_number(value) {

}

var huoshu             = {};
huoshu.ui              = {};
huoshu.ui.popup_iframe = function (title, url) {
    layer.open({
        shift     : 7,
        type      : 2,
        maxmin    : true,
        title     : title,
        shadeClose: true,
        shade     : 0.2,
        area      : ['850px', '60%'],
        content   : url
    });
};

huoshu.ui.popup_div = function (title, selector) {
    layer.open({
        type   : 1,
        shift  : 2,
        area   : '350px',
        title  : title,
        content: $(selector),
        cancel : function (index) {
            layer.close(index);
        }
    });
};

function imgnofind(url) {
    var img     = event.srcElement;
    img.src     = url;
    img.onerror = null; //控制不要一直跳动
}

function clearNoNum(obj, min, max, fix) {
    if (! fix) {
        fix = 4;
    }
    if (obj.value.indexOf(".") == 0) {
        //首位为小数点，自动补齐0
        obj.value = "0" + obj.value;
    }
    obj.value = obj.value.replace(/[^\d.]/g, ""); // 清除“数字”和“.”以外的字符
    obj.value = obj.value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
    obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
    if (4 == fix) {
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d\d\d).*$/, "$1$2.$3");//只能输入四个小数
    } else {
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, "$1$2.$3");//只能输入两位小数
    }

    if (obj.value.indexOf(".") < 0 && obj.value != "") {
        //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
        obj.value = parseFloat(obj.value);
    }

    if (obj.value < min) {
        obj.value = min;
    }
    if (max || 0 != max) {
        if (obj.value > max) {
            obj.value = max;
        }
    }
}