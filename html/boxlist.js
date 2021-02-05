var card_modal_name = '#card-view';
function addRowHandlers() {

    var table = document.getElementById("boxes-table");
    var rows = table.getElementsByTagName("tr");
    for (i = 0; i < rows.length; i++) {
        var currentRow = rows[i];
        var cells = currentRow.getElementsByTagName("td");
        for (j = 0; j < cells.length; j++) {
            currentCell = cells[j]
            var createClickHandler = function (row) {
                return function () {
                    var headerCell = row.getElementsByTagName("th")[0];
                    var box_id = headerCell.innerHTML;
                    window.location.href = "./BoxView.php?box_id=" + box_id

                };
            };
            if (j != (cells.length - 1)) {
                currentCell.onclick = createClickHandler(currentRow);
            }

        }

        console.log(" i is " + i);

    }
}
function submitForm() {
    console.log("hello con");
    document.fbox.submit();

}



addRowHandlers();