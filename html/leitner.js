var wrapper_modal_name = '.modal-wrapper'
var create_card_modal_name = '#create-card-modal'
var card_modal_name = '#card-view'

var close_all_modals = (event, out_click=false) => {
    event.preventDefault()
    if (out_click)
        if (get_element(wrapper_modal_name) !== event.target)
                return
    change_element_display(get_element(wrapper_modal_name), 'none')
    change_element_display(get_element(create_card_modal_name), 'none')
    change_element_display(get_element(card_modal_name), 'none')
}

var get_element = (name) => document.querySelector(name)
var change_element_display = (element, status) => element.style.display = status

var open_create_card_modal = (event) => {
    event.preventDefault();
    
    change_element_display(get_element(wrapper_modal_name), 'flex');
    change_element_display(get_element(create_card_modal_name), 'block');
    change_element_display(get_element(card_modal_name), 'none');
}

var open_card_modal = (
    event,
    id,
    front_text,
    front_image,
    front_audio,
    back_text,
    back_image,
    back_audio,
) => {
    event.preventDefault()
    console.log(front_text)

    // first get the elements and replace with inputs
    el = get_element(card_modal_name)
    if (front_text) {
        el.innerHTML = `
            <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

            <img src="`+ front_image +`" alt="alternative">
            <audio controls>
                <source src="` + front_audio + `">
                Your browser does not support the audio tag.
            </audio>
            <div class="card-text">
                <p>` + front_text + `</p>
            </div>
            <form action="" method="POST">
                <input type="hidden" id="action" name="action" value="answer_card">
                <input type="hidden" id="card_id" name="card_id" value="` + id + `">

                <input required="required" class="text-inp" type="text" name="answer" id="answer" placeholder="جواب را وارد کنید">
                <button class="ltn-button">بفرست</button>
            </form>
        `
    } else if (back_text) {
        el.innerHTML = `
            <div class="close-modal-btn" onclick="close_all_modals(event)"></div>

            <img src="`+ back_image +`" alt="alternative">
            <audio controls>
                <source src="` + back_audio + `">
                Your browser does not support the audio tag.
            </audio>
            <div class="card-text">
                <p>` + back_text + `</p>
            </div>

        `
    }

    // then display them
    change_element_display(get_element(wrapper_modal_name), 'flex')
    change_element_display(get_element(card_modal_name), 'block')
}