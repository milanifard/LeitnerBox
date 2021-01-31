var wrapper_modal_name = '.modal-wrapper'
var create_card_modal_name = '.create-card-modal'

var close_all_modals = (event, out_click=false) => {
    event.preventDefault()
    if (out_click)
        if (get_element(wrapper_modal_name) !== event.target)
                return
    change_element_display(get_element(wrapper_modal_name), 'none')
    change_element_display(get_element(create_card_modal_name), 'none')
}

var get_element = (name) => document.querySelector(name)
var change_element_display = (element, status) => element.style.display = status

var open_create_card_modal = (event) => {
    event.preventDefault()

    change_element_display(get_element(wrapper_modal_name), 'flex')
    change_element_display(get_element(create_card_modal_name), 'block')
}

const Click =(event)=>{
    event.preventDefault()

    console.log('555555');
}