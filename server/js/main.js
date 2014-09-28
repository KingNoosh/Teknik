var data_dbg;
$(document).ready(function () {

    $(document).ajaxStart(function () {
        $("#loader").show();
    });
    $(document).ajaxStop(function () {
        $("#loader").hide();
    });

    $.ajax({
        dataType: "json",
        url: "json.php?plugin=complete",
        success: function (data) {
            console.log(data);
            data_dbg = data;
            renderVitals(data);
            renderHardware(data);
            renderMemory(data);
            renderFilesystem(data);
            renderNetwork(data);
            renderVoltage(data);
            renderTemperature(data);
            renderFans(data);
            renderPower(data);
            renderCurrent(data);

            // Rendering plugins
            if (data['Plugins'] != undefined) {

                for (plugin in data['Plugins']) {
                    // dynamic call
                    window['renderPlugin' + plugin](data['Plugins'][plugin]);
                }

            }
        }
    });
});

function renderVitals(data) {
    var directives = {
        Uptime: {
            text: function () {
                return secondsToString(this["Uptime"]);
            }
        },
        Distro: {
            html: function () {
                return '<img src="gfx/images/' + this["Distroicon"] + '" style="width:24px"/>' + this["Distro"];
            }
        }
    };

    if (data["Vitals"]["SysLang"] === undefined) {
        $("#tr_SysLang").hide();
    }
    if (data["Vitals"]["CodePage"] === undefined) {
        $("#tr_CodePage").hide();
    }
    $('#vitals').render(data["Vitals"], directives);
}

function renderHardware(data) {

    var directives = {
        Model: {
            text: function () {
                if(this["CPU"].length > 1)
                    return this["CPU"].length + " x " + this["CPU"][0]["Model"];
                else
                    return this["CPU"][0]["Model"];
            }
        }
    };
    $('#hardware').render(data["Hardware"], directives);

    var hw_directives = {
        hwName: {
            text: function() {
                return this["Name"];
            }
        },
        hwCount: {
            text: function() {
                if (this["Count"] == "1") {
                    return "";
                }
                return this["Count"];
            }
        }
    };

    for (hw_type in data["Hardware"]) {
        if (hw_type != "CPU") {
            try {
                hw_data = [];
                if (data["Hardware"][hw_type].length > 0) {
                    for (i=0; i < data["Hardware"][hw_type].length; i++) {
                        hw_data.push(data["Hardware"][hw_type][i]);
                    }
                }
                if (hw_data.length > 0) {
                    $("#hardware-" + hw_type + " span").html(hw_data.length);
                    $("#hw-dialog-"+hw_type+" ul").render(hw_data, hw_directives);
                    $("#hardware-"+hw_type).show();
                }
                else {
                    $("#hardware-"+hw_type).hide();
                }
            }
            catch (err) {
                $("#hardware-"+hw_type).hide();
            }
        }
    }
}

function renderMemory(data) {
    var directives = {
        Total: {
            text: function () {
                return bytesToSize(this["Total"]);
            }
        },
        Free: {
            text: function () {
                return bytesToSize(this["Free"]);
            }
        },
        Used: {
            text: function () {
                return bytesToSize(this["Used"]);
            }
        },
        Usage: {
            html: function () {
                if (this["Details"] == undefined) {
                    return '<div class="progress">' +
                        '<div class="progress-bar progress-bar-info" style="width: ' + this["Percent"] + '%;"></div>' +
                        '</div><div class="percent">' + this["Percent"] + '%</div>';
                }
                else {
                    return '<div class="progress">' +
                        '<div class="progress-bar progress-bar-info" style="width: ' + this["Details"]["AppPercent"] + '%;"></div>' +
                        '<div class="progress-bar progress-bar-warning" style="width: ' + this["Details"]["CachedPercent"] + '%;"></div>' +
                        '<div class="progress-bar progress-bar-danger" style="width: ' + this["Details"]["BuffersPercent"] + '%;"></div>' +
                        '</div>' +
                        '<div class="percent">' +
                        'Total: ' + this["Percent"] + '% ' +
                        '<i>(App: ' + this["Details"]["AppPercent"] + '% - ' +
                        'Cache: ' + this["Details"]["CachedPercent"] + '% - ' +
                        'Buffers: ' + this["Details"]["BuffersPercent"] + '%' +
                        ')</i></div>';
                }
            }
        },
        Type: {
            text: function () {
                return "Physical Memory";
            }
        }
    };

    var directive_swap = {
        Total: {
            text: function () {
                return bytesToSize(this["Total"]);
            }
        },
        Free: {
            text: function () {
                return bytesToSize(this["Free"]);
            }
        },
        Used: {
            text: function () {
                return bytesToSize(this["Used"]);
            }
        },
        Usage: {
            html: function () {
                return '<div class="progress">' +
                    '<div class="progress-bar progress-bar-info" style="width: ' + this["Percent"] + '%;"></div>' +
                    '</div><div class="percent">' + this["Percent"] + '%</div>';
            }
        },
        Name: {
            html: function () {
                return this['Name'] + '<br/>' + this['MountPoint'];
            }
        }
    }

    var data_memory = [];

    if (data["Memory"]["Swap"]["Mount"] !== undefined) {
        for (var i = 0; i < data["Memory"]["Swap"]["Mount"].length; i++) {
            data_memory.push(data["Memory"]["Swap"]["Mount"][i]);
        }
    }

    $('#memory-data').render(data["Memory"], directives);
    $('#swap-data').render(data_memory, directive_swap);
}

function renderFilesystem(data) {
    var directives = {
        Total: {
            text: function () {
                return bytesToSize(this["Total"]);
            }
        },
        Free: {
            text: function () {
                return bytesToSize(this["Free"]);
            }
        },
        Used: {
            text: function () {
                return bytesToSize(this["Used"]);
            }
        },
        MountPoint: {
            text: function () {
                return ((this["MountPoint"] !== undefined) ? this["MountPoint"] : this["MountPointID"]);
            }
        },
        Name: {
            html: function () {
                return this["Name"] + ((this["MountOptions"] !== undefined) ? '<br><i>(' + this["MountOptions"] + ')</i>' : '');
            }
        },
        Percent: {
            html: function () {
                return '<div class="progress">' + '<div class="' +
                    ((!isNaN(data["Options"]["threshold"]) &&
                        (this["Percent"] >= data["Options"]["threshold"])) ? 'progress-bar progress-bar-danger' : 'progress-bar progress-bar-info') +
                    '" style="width: ' + this["Percent"] + '% ;"></div>' +
                    '</div>' + '<div class="percent">' + this["Percent"] + '% ' + (!isNaN(this["Inodes"]) ? '<i>(' + this["Inodes"] + '%)</i>' : '') + '</div>';
            }
        }
    };

    try {
        var fs_data = [];
        for (var i = 0; i < data["FileSystem"]["Mount"].length; i++) {
            fs_data.push(data["FileSystem"]["Mount"][i]);
        }
        $('#filesystem-data').render(fs_data, directives);
        sorttable.innerSortFunction.apply(document.getElementById('MountPoint'), []);
        $("#block_filesystem").show();
    }
    catch (err) {
        $("#block_filesystem").hide();
    }
}


function renderNetwork(data) {
    var directives = {
        RxBytes: {
            text: function () {
                return bytesToSize(this["RxBytes"]);
            }
        },
        TxBytes: {
            text: function () {
                return bytesToSize(this["TxBytes"]);
            }
        },
        Drops: {
            text: function () {
                return this["Drops"] + "/" + this["Err"];
            }
        }
    };

    try {
        var network_data = [];
        for (var i = 0; i < data["Network"]["NetDevice"].length; i++) {
            network_data.push(data["Network"]["NetDevice"][i]);
        }
        $('#network-data').render(network_data, directives);
        $("#block_network").show();
    }
    catch (err) {
        $("#block_network").hide();
    }
}

function renderVoltage(data) {
    var directives = {
        Label: {
            text: function () {
                if (this["Event"] === undefined)
                    return this["Label"];
                else
                    return this["Label"] + " ! "+this["Event"];

            }
        }
    };
    try {
        var voltage_data = [];
        for (var i = 0; i < data["MBInfo"]["Voltage"].length; i++) {
            voltage_data.push(data["MBInfo"]["Voltage"][i]);
        }
        $('#voltage-data').render(voltage_data, directives);
        $("#block_voltage").show();
    }
    catch (err) {
        $("#block_voltage").hide();
    }
}

function renderTemperature(data) {
    var directives = {
        Value: {
            text: function () {
                return this["Value"] + data["Options"]["tempFormat"];
            }
        },
        Label: {
            text: function () {
                if (this["Event"] === undefined)
                    return this["Label"];
                else
                    return this["Label"] + " ! "+this["Event"];
            }
        }
    };

    try {
        var temperature_data = [];
        for (var i = 0; i < data["MBInfo"]["Temperature"].length; i++) {
            temperature_data.push(data["MBInfo"]["Temperature"][i]);
        }
        $('#temperature-data').render(temperature_data, directives);
        $("#block_temperature").show();
    }
    catch (err) {
        $("#block_temperature").hide();
    }
}

function renderFans(data) {
    var directives = {
        Label: {
            text: function () {
                if (this["Event"] === undefined)
                    return this["Label"];
                else
                    return this["Label"] + " ! "+this["Event"];
            }
        }
    };

    try {
        var fans_data = [];
        for (var i = 0; i < data["MBInfo"]["Fans"].length; i++) {
            fans_data.push(data["MBInfo"]["Fans"][i]);
        }
        $('#fans-data').render(fans_data, directives);
        $("#block_fans").show();
    }
    catch (err) {
        $("#block_fans").hide();
    }
}

function renderPower(data) {
    var directives = {
        Label: {
            text: function () {
                if (this["Event"] === undefined)
                    return this["Label"];
                else
                    return this["Label"] + " ! "+this["Event"];
            }
        }
    };

    try {
        var power_data = [];
        for (var i = 0; i < data["MBInfo"]["Power"].length; i++) {
            power_data.push(data["MBInfo"]["Power"][i]);
        }
        $('#power-data').render(power_data, directives);
        $("#block_power").show();
    }
    catch (err) {
        $("#block_power").hide();
    }
}

function renderCurrent(data) {
    var directives = {
        Label: {
            text: function () {
                if (this["Event"] === undefined)
                    return this["Label"];
                else
                    return this["Label"] + " ! "+this["Event"];
            }
        }
    };

    try {
        var current_data = [];
        for (var i = 0; i < data["MBInfo"]["Current"].length; i++) {
            current_data.push(data["MBInfo"]["Current"][i]);
        }
        $('#current-data').render(current_data, directives);
        $("#block_current").show();
    }
    catch (err) {
        $("#block_current").hide();
    }
}

// from http://scratch99.com/web-development/javascript/convert-bytes-to-mb-kb/
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    if (i == 0) return bytes + '' + sizes[i];
    return (bytes / Math.pow(1024, i)).toFixed(1) + '' + sizes[i];
}

function secondsToString(seconds) {
    var numyears = Math.floor(seconds / 31536000);
    var numdays = Math.floor((seconds % 31536000) / 86400);
    var numhours = Math.floor(((seconds % 31536000) % 86400) / 3600);
    var numminutes = Math.floor((((seconds % 31536000) % 86400) % 3600) / 60);
    var numseconds = Math.floor((((seconds % 31536000) % 86400) % 3600) % 60);
    return numyears + " years " + numdays + " days " + numhours + " hours " + numminutes + " minutes " + numseconds + " seconds";
}
