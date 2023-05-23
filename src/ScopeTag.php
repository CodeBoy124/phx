<?php
function Scopes($props)
{
    global ${$props["from"]};
    return ${$props["from"]}->Read();
}
