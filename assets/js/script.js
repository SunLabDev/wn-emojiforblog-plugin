$(function () {
    var emojiField = document.getElementById("Form-field-Post-_emoji_field-group")
    var li = document.createElement('li');

    li.appendChild(emojiField)
    $("#Form-secondaryTabs .nav.nav-tabs").append(li)
})
