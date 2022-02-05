require('./bootstrap');

window.vue = new Vue({
    el: '#main',

    data: {
        bShowRegister: false,
        bShowLogin: false,
        bShowRecover: false,

        translationTable: {
            usernameOk: 'The given name is valid and available',
            invalidUsername: 'The name is invalid. Please use only alphanumeric characters, numbers 0-9 and the characters \'-\' and \'_\'. Also number only identifiers are considered invalid',
            nonavailableUsername: 'The given name is already in use',
            passwordMismatching: 'The passwords do not match',
            passwordMatching: 'The passwords do match',
        },
    },

    methods: {
        invalidLoginEmail: function () {
            let el = document.getElementById("loginemail");

            if ((el.value.length == 0) || (el.value.indexOf('@') == -1) || (el.value.indexOf('.') == -1)) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        invalidRecoverEmail: function () {
            let el = document.getElementById("recoveremail");

            if ((el.value.length == 0) || (el.value.indexOf('@') == -1) || (el.value.indexOf('.') == -1)) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        invalidLoginPassword: function () {
            let el = document.getElementById("loginpw");

            if (el.value.length == 0) {
                el.classList.add('is-danger');
            } else {
                el.classList.remove('is-danger');
            }
        },

        showUsernameValidity: function(username, hint, currentName = '') {
            window.vue.ajaxRequest('get', window.location.origin + '/member/username/valid?ident=' + username, {}, function(response) {
                if (response.code == 200) {
                    if ((currentName !== '') && (username === currentName)) {
                        hint.innerHTML = '';
                    } else if (!response.data.valid) {
                        hint.classList.add('is-danger');
                        hint.classList.remove('is-success');
                        hint.innerHTML = window.vue.translationTable.invalidUsername;
                    } else if (!response.data.available) {
                        hint.classList.add('is-danger');
                        hint.classList.remove('is-success');
                        hint.innerHTML = window.vue.translationTable.nonavailableUsername;
                    } else if ((response.data.valid) && (response.data.available)) {
                        hint.classList.remove('is-danger');
                        hint.classList.add('is-success');
                        hint.innerHTML = window.vue.translationTable.usernameOk;
                    }
                }
            });
        },

        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
        {
            let func = window.axios.get;
            if (method == 'post') {
                func = window.axios.post;
            } else if (method == 'patch') {
                func = window.axios.patch;
            } else if (method == 'delete') {
                func = window.axios.delete;
            }

            func(url, data, config)
                .then(function(response){
                    successfunc(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function(){
                        finalfunc();
                }
            );
        },

        initNavbar: function() {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    
            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        },

        showUsernameValidity: function(username, hint, currentName = '') {
            window.vue.ajaxRequest('get', window.location.origin + '/member/name/valid?ident=' + username, {}, function(response) {
                if (response.code == 200) {
                    if ((currentName !== '') && (username === currentName)) {
                        hint.innerHTML = '';
                    } else if (!response.data.valid) {
                        hint.classList.add('is-danger');
                        hint.classList.remove('is-success');
                        hint.innerHTML = window.vue.translationTable.invalidUsername;
                    } else if (!response.data.available) {
                        hint.classList.add('is-danger');
                        hint.classList.remove('is-success');
                        hint.innerHTML = window.vue.translationTable.nonavailableUsername;
                    } else if ((response.data.valid) && (response.data.available)) {
                        hint.classList.remove('is-danger');
                        hint.classList.add('is-success');
                        hint.innerHTML = window.vue.translationTable.usernameOk;
                    }
                }
            });
        },
        
        showPasswordMatching: function(pw1, pw2, hint) {
            if ((pw1.length > 0) || (pw2.length > 0)) {
                if (pw1 !== pw2) {
                    hint.classList.remove('is-success');
                    hint.classList.add('is-danger');
                    hint.innerHTML = window.vue.translationTable.passwordMismatching;
                } else {
                    hint.classList.add('is-success');
                    hint.classList.remove('is-danger');
                    hint.innerHTML = window.vue.translationTable.passwordMatching;
                }
            }
        },

        showError: function()
        {
            document.getElementById('flash-error').style.display = 'inherit';
            setTimeout(function() { document.getElementById('flash-error').style.display = 'none'; }, 3500);
        },

        showSuccess: function()
        {
            document.getElementById('flash-success').style.display = 'inherit';
            setTimeout(function() { document.getElementById('flash-success').style.display = 'none'; }, 3500);
        },

        toggleDropdown: function(obj) {
            if (obj) {
                obj.classList.toggle('is-hidden');
            }
        },

        renderFrameworkItem: function(elem) {
            let tags = '';

            elem.tags.forEach(function(tag, index){
                tags += '<span><a href="">#' + tag + '</a>&nbsp;</span>';
            });

            let html = `
                <div class="framework-item is-pointer" onclick="location.href = '` + window.location.origin + `/view/` + elem.slug + `';">
                    <div class="framework-item-image" style="background-image: url('` + window.location.origin + '/gfx/logos/' + elem.logo + `')"></div>

                    <div class="framework-item-about">
                        <div class="framework-item-about-title">` + elem.name + `</div>
                        <div class="framework-item-about-hint">` + elem.summary + `</div>
                        <div class="framework-item-about-tags">` + tags + `</div>
                    </div>

                    <div class="framework-item-stats">
                        <div class="framework-item-stats-hearts">
                            <i class="fas fa-heart"></i>&nbsp;` + elem.hearts + `
                        </div>

                        <div class="framework-item-stats-views">
                            <i class="far fa-eye"></i>&nbsp;` + elem.views + `
                        </div>
                    </div>
                </div>
            `;

            return html;
        },

        renderReview: function(elem, user, isAdmin = false) {
            let stars = '';
            
            for (let i = 0; i < elem.stars; i++) {
                stars += '<span class="review-star-color"><i class="fas fa-star"></i></span>';
            }

            if (elem.stars < 5) {
                for (let j = elem.stars; j < 5; j++) {
                    stars += '<span class="review-star-color"><i class="far fa-star"></i></span>';
                }
            }

            let options = '';

            if (elem.userData.id != user) {
                options += '<div class="review-footer-option"><a href="">Report</a>&nbsp;</div>';
            }

            if ((isAdmin) || (elem.userData.id == user)) {
                options += '<div class="review-footer-option"><a href="">Delete</a>&nbsp;</div>';
            }

            let html = `
                <div class="review">
                    <div class="review-header">
                        <div class="review-header-left">
                            <img src="` + window.location.origin + '/gfx/avatars/' + elem.userData.avatar + `" width="64" height="64">
                        </div>

                        <div class="review-header-right">
                            <div class="review-header-right-username"><a href="">` + elem.userData.username + `</a></div>
                            
                            <div class="review-header-right-stars">` + stars + `</div>
                        </div>
                    </div>

                    <div class="review-content">` + elem.content + `</div>

                    <div class="review-footer">
                        ` + options + `
                    </div>
                </div>
            `;

            return html;
        }
    }
});