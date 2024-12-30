const closing_btn = document.getElementById('closing');
        const opening_btn = document.getElementById('opening');
        const soldout_btn = document.getElementById('soldout');
        const cancel_btn = document.getElementById('cancel');
        const sortNumDown_id_icon = document.getElementById('sortNumDown_id_icon');
        const sortNumUp_id_icon = document.getElementById('sortNumUp_id_icon');
        const sortAlphaDown_rap_icon = document.getElementById('sortAlphaDown_rap_icon');
        const sortAlphaUp_rap_icon = document.getElementById('sortAlphaUp_rap_icon');

        function optionOfList(button) {
            if (button.id == 'closing') {
                closing_btn.classList.add('button-nav-active');
                setupButtonInavActive(opening_btn);
                setupButtonInavActive(soldout_btn);
                setupButtonInavActive(cancel_btn);
            } else if (button.id == 'opening') {
                opening_btn.classList.add('button-nav-active');
                setupButtonInavActive(closing_btn); 
                setupButtonInavActive(soldout_btn);
                setupButtonInavActive(cancel_btn);
            } else if (button.id == 'soldout') {
                soldout_btn.classList.add('button-nav-active');
                setupButtonInavActive(closing_btn);
                setupButtonInavActive(opening_btn);
                setupButtonInavActive(cancel_btn);
            } else {
                cancel_btn.classList.add('button-nav-active');
                setupButtonInavActive(closing_btn);
                setupButtonInavActive(opening_btn);
                setupButtonInavActive(soldout_btn);
            }
        }

        function setupButtonInavActive(button) {
            button.classList.remove('button-nav-active');
        }