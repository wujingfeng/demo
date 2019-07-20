function login(){
    var user = document.getElementById('user');
    var username = user.value;

    var pwd = document.getElementById('password');
    var password = pwd.value

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
            //接收消息 
            var data = xmlHttp.responseText;
            
            //todo 后续处理
        }
    }

    xmlHttp.open();
}