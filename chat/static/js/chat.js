
window.onload = function () {
    var div1 = document.getElementById('content');
    div1.scrollTop = div1.scrollHeight;
    //初始化展示两条信息
    var leftMsg = 'XXX好友上线了'
    var initLeft = createLeftDiv('发送方方', leftMsg);
    appendMsg(initLeft)
    var rightMsg = "收到XXX好友上线提示"
    var initRight = createRightDiv('接收方', rightMsg);
    appendMsg(initRight)

}


function sendMsg() {
    var text = document.getElementById('textMsg');
    message = text.value

    if (message !== '') {
        var newDiv = createRightDiv('接收方', message);
        appendMsg(newDiv)
        text.value = '';

    }

}

function appendMsg(newDiv) {
    contentDiv = document.getElementById('content');
    contentDiv.appendChild(newDiv);

    var div1 = document.getElementById('content');
    div1.scrollTop = div1.scrollHeight;
}

// 创建自己发送的消息框
function createRightDiv(user, msg) {
    var contentRightDiv = document.createElement('div');
    contentRightDiv.className = 'contentRight';

    var receiveContentDiv = document.createElement('div');
    receiveContentDiv.className = "receiveContent";

    var clearfloatDiv = document.createElement('div');
    clearfloatDiv.className = "clearfloat";
    contentRightDiv.appendChild(receiveContentDiv)
    contentRightDiv.appendChild(clearfloatDiv)

    var receiveInfoDiv = document.createElement('div');
    receiveInfoDiv.className = "receiveInfo";

    var receiveMsg = document.createElement('div');
    receiveMsg.className = "receiveMsg msgBox";

    receiveContentDiv.appendChild(receiveInfoDiv)
    receiveContentDiv.appendChild(receiveMsg)

    var spanInfo = document.createElement('span');
    spanInfo.innerText = user
    receiveInfoDiv.appendChild(spanInfo)

    var spanMsg = document.createElement('span');
    spanMsg.innerText = msg
    receiveMsg.appendChild(spanMsg)

    return contentRightDiv;

}


// 创建好友发送的消息框
function createLeftDiv(user, msg) {
    var contentLeftDiv = document.createElement('div');
    contentLeftDiv.className = 'contentLeft';

    var sendContentDiv = document.createElement('div');
    sendContentDiv.className = "sendContent";

    var clearfloatDiv = document.createElement('div');
    clearfloatDiv.className = "clearfloat";
    contentLeftDiv.appendChild(sendContentDiv)
    contentLeftDiv.appendChild(clearfloatDiv)

    var sendInfoDiv = document.createElement('div');
    sendInfoDiv.className = "sendInfo";

    var sendMsg = document.createElement('div');
    sendMsg.className = "sendMsg msgBox";

    sendContentDiv.appendChild(sendInfoDiv)
    sendContentDiv.appendChild(sendMsg)

    var spanInfo = document.createElement('span');
    spanInfo.innerText = user
    sendInfoDiv.appendChild(spanInfo)

    var spanMsg = document.createElement('span');
    spanMsg.innerText = msg
    sendMsg.appendChild(spanMsg)

    return contentLeftDiv;

}

function enterMsg(keyCode) {
    var text = document.getElementById('textMsg');
    message = text.value
    if (keyCode == 13) {
        window.event.preventDefault();
        if (message != '') {
            sendMsg();
        }
        text.value = ''
    }

}

function showScroll() {
    var contentDiv = document.getElementById('content')
    contentDiv.setAttribute('.content::-webkit-scrollbar')

}
