var clipboard = new Clipboard('.link_copy_btn');
clipboard.on('success', function (e) {
    alert("复制链接成功！");
});
clipboard.on('error', function (e) {
    alert("复制链接失败，您的浏览器不支持此功能，请右键单击选择复制！");
});

