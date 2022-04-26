//注意：选项卡 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function () {
    var element = layui.element;

    //JavaScript
    element.tab({
        headerElem: '#tabHeader>li' //指定tab头元素项
        , bodyElem: '#tabBody>.layui-tab-item' //指定tab主体元素项
    });
});