$(function() {
    var biggest = 0;
    $("button").each(function(i) {
        if ($(this).width() > biggest) { biggest = $(this).width(); }
    });
    biggest += 5;
    $("#box1Group input[name='filter']").keyup(function() {
        Filter('box1Group', $(this).val());
    });
    $("#box2Group input[name='filter']").keyup(function() {
        Filter('box2Group', $(this).val());
    });
    $("#box1Group button[name='clear']").click(function() {
        ClearFilter('box1Group');
    });
    $("#box2Group button[name='clear']").click(function() {
        ClearFilter('box2Group');
    });
    $("#box1Group select[name='view']").dblclick(function() {
        MoveSelected('box1Group', 'box2Group');
    });
    $("#box2Group select[name='view']").dblclick(function() {
        RemoveSelected('box2Group', 'box1Group');
    });
    $("#to2").click(function() {
        MoveSelected('box1Group', 'box2Group');
    }).width(biggest);
    $("#allTo2").click(function() {
        MoveAll('box1Group', 'box2Group');
    }).width(biggest);
    $("#allTo1").click(function() {
        RemoveAll('box2Group', 'box1Group');
    }).width(biggest);
    $("#to1").click(function() {
        RemoveSelected('box2Group', 'box1Group');
    }).width(biggest);
    $("#btnSubmit").width(biggest);
    UpdateLabel("box1Group");
    UpdateLabel("box2Group");
});

function UpdateLabel(labelGroupID) {
    var $options = $("#" + labelGroupID + " select[name='view'] option");
    $("#" + labelGroupID + " span[class='countLabel']").text('Showing ' + $options.size() + ' of ' + $options.add("#" + labelGroupID + " select[name='storage'] option").size());
}

function Filter(toGroupID, filter) {
    $("#" + toGroupID + " select[name='view'] option").filter(function(i) {
        var toMatch = $(this).text().toString().toLowerCase();
        var filterLower = filter.toString().toLowerCase();
        return toMatch.indexOf(filterLower) == -1;
    }).appendTo("#" + toGroupID + " select[name='storage']");
    $("#" + toGroupID + " select[name='storage'] option").filter(function(i) {
        var toMatch = $(this).text().toString().toLowerCase();
        var filterLower = filter.toString().toLowerCase();
        return toMatch.indexOf(filterLower) != -1;
    }).appendTo("#" + toGroupID + " select[name='view']");
    SortOptions(toGroupID);
    UpdateLabel(toGroupID);
}

function SortOptions(toSortGroupID) {
    var $toSortOptions = $("#" + toSortGroupID + " select[name='view'] option");
    $toSortOptions.sort(function(a, b) {
        var aVal = a.text.toLowerCase();
        var bVal = b.text.toLowerCase();
        if (aVal < bVal) { return -1; }
        if (aVal > bVal) { return 1; }
        return 0;
    });
    $("#" + toSortGroupID + " select[name='view']").empty().append($toSortOptions);
}

function MoveSelected(fromGroupID, toGroupID) {
    $("#" + fromGroupID + " select[name='view'] option:selected:not([class*=copiedOption])").clone().appendTo("#" + toGroupID + " select[name='view']").end().end().addClass('copiedOption').removeAttr('selected');
    Filter(toGroupID, $("#" + toGroupID + " input[name='filter']").val());
    UpdateLabel(fromGroupID);
}
function MoveAll(fromGroupID, toGroupID) {
    $("#" + fromGroupID + " select[name='view'] option").attr('selected', 'selected');
    MoveSelected(fromGroupID, toGroupID);
}

function ClearFilter(toClearGroupID) {
    $("#" + toClearGroupID + " input[name='filter']").val("");
    Filter(toClearGroupID, "");
}

function RemoveSelected(removeFromGroupID, otherGroupID) {
    $("#" + otherGroupID + " select[name='view'] option.copiedOption").add("#" + otherGroupID + " select[name='storage'] option.copiedOption").remove();
    $("#" + removeFromGroupID + " select[name='view'] option:selected").removeAttr('selected').appendTo("#" + otherGroupID + " select[name='view']");
    $("#" + removeFromGroupID + " select[name='view'] option").add("#" + removeFromGroupID + " select[name='storage'] option").clone().addClass('copiedOption').appendTo("#" + otherGroupID + " select[name='view']");
    Filter(otherGroupID, $("#" + otherGroupID + " input[name='filter']").val());
    UpdateLabel(removeFromGroupID);
}

function RemoveAll(removeFromGroupID, otherGroupID) {
    $("#" + removeFromGroupID + " select[name='view'] option").attr('selected', 'selected');

    RemoveSelected(removeFromGroupID, otherGroupID);
}

