function copyClipboard(id)
{
    var tempInput = document.createElement("input");
    tempInput.value = "http://localhost/invitesystem/register.php?invite=" + id;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
}