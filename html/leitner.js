var wrapper_modal_name = ".modal-wrapper";
var create_card_modal_name = "#create-card-modal";
var card_modal_name = "#card-view";
var editBoxById = (id) => {
  // first get the elements and replace with inputs
  el = get_element(card_modal_name);
  $box_id = 1;
  el.innerHTML = `
        <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

        <form action="./LeitnerBox.php" method="post">
        <input type="hidden" name="id" value=${id}>
        <div class="form-group">
            <label class="mt-4" for="box-name">نام جعبه</label>
            <input type="text" class="form-control" name="edit-box-name">
        </div>
        <div class="form-group">
            <label for="box-description">توضیحات جعبه</label>
            <textarea class="form-control" name="edit-box-description" rows="3"></textarea>
        </div>
        <button type="submit" class="ltn-button">ویرایش</button>
    </form>
    `;
  // then display them

  change_element_display(get_element(card_modal_name), "block");
};
var editCardById = (id) => {
  // first get the elements and replace with inputs
  el = get_element(card_modal_name);
  $box_id = document.getElementsByClassName("leitner-header")[0].id;
  el.innerHTML = `
    <div class="close-modal-btn" onclick="close_all_modals(event)"></div>
    <h3>ویرایش کارت</h3>
    <form action='../www/sadaf/BoxView.php?box_id=${$box_id}' method="POST" enctype="multipart/form-data">
        <input type="hidden" name="edit_card" value="${id}">

        <input class="text-inp form-control" type="text" name="front_text" id="front_text" placeholder="متن جلوی کارت">
        <div class="file-input-wrapper">
            <input type="file" id="front_image" name="front_image" accept="image/*">
            <span>آپلود عکس جلوی کارت</span>
        </div>
        <div class="file-input-wrapper">
            <input type="file" id="front_audio" name="front_audio" accept="audio/*">
            <span>آپلود صدای جلوی کارت</span>
        </div>
        <input class="text-inp form-control" type="text" name="back_text" id="back_text" placeholder="متن پشت کارت">
        <div class="file-input-wrapper">
            <input type="file" id="back_image" name="back_image" accept="image/*">
            <span>آپلود عکس پشت کارت</span>
        </div>
        <div class="file-input-wrapper">
            <input type="file" id="back_audio" name="back_audio" accept="audio/*">
            <span>آپلود صدای پشت کارت</span>
        </div>
        <button class="ltn-button">ویرایش</button>
    </form>
   `;
  // then display them
  change_element_display(get_element(wrapper_modal_name), "flex");
  change_element_display(get_element(card_modal_name), "block");
};
var Import = () => {
  event.preventDefault();

  // first get the elements and replace with inputs
  el = get_element(card_modal_name);

  el.innerHTML = `
        <div class="close-modal-btn" onclick="close_all_modals(event)"></div>
    <h3>
    Import
    </h3>
    <br>
    <form action="../../LeitnerBox/config/box_import.php"  enctype="multipart/form-data" method="post">
    <p>
    محل آپلود فایل :   <input style="float:left;" type="file" name="import" accept="zip/*" required>
    </p>
    <br> 
    <button type="submit" class="ltn-button">Import</button>
    </form>
         
            `;

  // then display them
  change_element_display(get_element(card_modal_name), "block");
};
var close_all_modals = (event, out_click = false) => {
  event.preventDefault();
  if (out_click) if (get_element(wrapper_modal_name) !== event.target) return;

  change_element_display(get_element(card_modal_name), "none");
  window.location.href = window.location.href;
};

var get_element = (name) => document.querySelector(name);
var change_element_display = (element, status) =>
  (element.style.display = status);

var open_create_card_modal = (event) => {
  event.preventDefault();

  change_element_display(get_element(wrapper_modal_name), "flex");
  change_element_display(get_element(create_card_modal_name), "block");
  change_element_display(get_element(card_modal_name), "none");
};

var open_card_modal = (
  event,
  id,
  front_text,
  front_image,
  front_audio,
  back_text,
  back_image,
  back_audio
) => {
  event.preventDefault();
  console.log(front_text);

  // first get the elements and replace with inputs
  el = get_element(card_modal_name);

  el.innerHTML =
    `
        <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

            

            <img onerror="this.onerror=null; this.src='placeholder.png'" src="` +
    front_image +
    `" alt="alternative" style="max-height:200px;"><br><br>
            <audio controls>
                <source src="` +
    front_audio +
    `">
                Your browser does not support the audio tag.
            </audio><br><br>
                ` +
    front_text +
    `
            <button  style="background:rgb(40,167,69)" class="ltn-button" onclick="flipp('${back_audio}' , '${back_image}' , '${back_text}' , '${id}')">مشاهده پشت کارت</button>
             `;

  // then display them
  change_element_display(get_element(wrapper_modal_name), "flex");
  change_element_display(get_element(card_modal_name), "block");
};
var delCard = (id) => {
  // first get the elements and replace with inputs
  el = get_element(card_modal_name);
  $box_id = document.getElementsByClassName("leitner-header")[0].id;
  el.innerHTML =
    `
        <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

            <div class="card-text">
                <p>` +
    "آیا مطمئن به حذف کارت هستید؟" +
    `</p>
            </div>
            <form action="` +
    window.location.href +
    `" method="POST"><input type="hidden" name="delCard" value="${$box_id},${id}"> <button type="submit" class="ltn-button">بله</button></form>
            <button class="ltn-button" onclick="close_all_modals(event)">خیر</button>
            `;

  // then display them
  change_element_display(get_element(wrapper_modal_name), "flex");
  change_element_display(get_element(card_modal_name), "block");
};
var flipp = (back_audio, back_image, back_text, card_id) => {
  el = get_element(card_modal_name);
  $box_id = document.getElementsByClassName("leitner-header")[0].id;

  el.innerHTML =
    ` <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

        <img onerror="this.onerror=null; this.src='placeholder.png'" src="` +
    back_image +
    `" alt="alternative" style="max-height:200px;"><br><br>
        <audio controls>
            <source src="` +
    back_audio +
    `">
            Your browser does not support the audio tag.
        </audio>
        <div class="card-text">
            <p>` +
    back_text +
    `</p>
        <form action="` +
    window.location.href +
    `" method="post" id="answer_card" ><input type="hidden" name="box_id" value="${$box_id}"><input type="hidden" name="answer_card" value="true" /><input type="hidden" name="card_id" value="` +
    card_id +
    `" /><p><button type="submit" form="answer_card"  class="btn btn-success">درست حدس زدم</button></form>
        </p>
        <form action="` +
    window.location.href +
    `" method="post" id="answer_cardf" ><input type="hidden" name="box_id" value="${$box_id}"><input type="hidden" name="answer_card" value="false" /><input type="hidden" name="card_id" value="` +
    card_id +
    `" /><button type="submit" form="answer_cardf"  class="btn btn-danger">اشتباه گفتم</button></form>
        </div>`;
};

function send_post_request(params) {
  console.log("request post sending");
  var http = new XMLHttpRequest();
  var url = window.location.href;
  http.open("POST", url, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  http.onreadystatechange = function () {
    //Call a function when the state changes.
    if (http.readyState == 4 && http.status == 200) {
      // console.log(http.responseText);
      console.log("request post done");
    }
  };
  http.send(params);
}
