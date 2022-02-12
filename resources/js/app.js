/*
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

require('./bootstrap');

window.vue = new Vue({
    el: '#main',

    data: {
        bShowRegister: false,
        bShowLogin: false,
        bShowRecover: false,
        bShowEditProfile: false,

        translationTable: {
            usernameOk: 'The given name is valid and available',
            invalidUsername: 'The name is invalid. Please use only alphanumeric characters, numbers 0-9 and the characters \'-\' and \'_\'. Also number only identifiers are considered invalid',
            nonavailableUsername: 'The given name is already in use',
            passwordMismatching: 'The passwords do not match',
            passwordMatching: 'The passwords do match',
            reviewCount: ':count reviews',
            confirmDeleteFramework: 'Do you really want to delete this framework item?',
            confirmDeleteReview: 'Do you really want to delete this review?',
            confirmDeleteAccount: 'Are you sure you want to delete your account? Please enter your password to proceed with deletion.'
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

        handleCookieConsent: function () {
            let cookies = document.cookie.split(';');
            let foundCookie = false;
            for (let i = 0; i < cookies.length; i++) {
                if (cookies[i].indexOf('cookieconsent') !== -1) {
                    foundCookie = true;
                    break;
                }
            }

            if (foundCookie === false) {
                document.getElementById('cookie-consent').style.display = 'inline-block';
            }
        },

        clickedCookieConsentButton: function () {
            let expDate = new Date(Date.now() + 1000 * 60 * 60 * 24 * 365);
            document.cookie = 'cookieconsent=1; expires=' + expDate.toUTCString() + ';';

            document.getElementById('cookie-consent').style.display = 'none';
        },

        toggleDropdown: function(obj) {
            if (obj) {
                obj.classList.toggle('is-hidden');
            }
        },

        toggleOverlay: function(ident) {
            let obj = document.getElementById(ident);
            if (obj) {
                if (obj.style.display === 'block') {
                    obj.style.display = 'none';
                } else {
                    obj.style.display = 'block';
                }
            }
        },

        markSeen: function() {
            this.ajaxRequest('get', window.location.origin + '/notifications/seen', {}, function(response) {
                if (response.code !== 200) {
                    console.log(response.msg);
                }
            });
        },

        renderFrameworkItem: function(elem) {
            let tags = '';

            elem.tags.forEach(function(tag, index){
                if (tag.length > 0) {
                    tags += '<span><a href="' + window.location.origin + '/?tag=' + tag + '">#' + tag + '</a>&nbsp;</span>';
                }
            });

            let stars = '';
            
            for (let i = 0; i < elem.avg_stars; i++) {
                stars += '<span class="review-star-color"><i class="fas fa-star"></i></span>';
            }

            if (elem.avg_stars < 5) {
                for (let j = elem.avg_stars; j < 5; j++) {
                    stars += '<span class="review-star-color"><i class="far fa-star"></i></span>';
                }
            }

            let html = `
                <div class="framework-item is-pointer" onclick="location.href = '` + window.location.origin + `/view/` + elem.slug + `';">
                    <div class="framework-item-image" style="background-image: url('` + window.location.origin + '/gfx/logos/' + elem.logo + `')"></div>

                    <div class="framework-item-about">
                        <div class="framework-item-about-title">` + elem.name + `</div>
                        <div class="framework-item-about-hint">` + elem.summary + `</div>
                        <div class="framework-item-about-tags">` + tags + `</div>
                    </div>

                    <div class="framework-item-stats">
                        <div class="framework-item-stats-stars">
                            ` + stars + `
                            ` + window.vue.translationTable.reviewCount.replace(':count', elem.review_count) + `
                        </div>

                        <div class="framework-item-stats-views">
                            <i class="far fa-eye"></i>&nbsp;` + elem.views + `
                        </div>
                    </div>
                </div>
            `;

            return html;
        },

        renderReview: function(elem, user, isAdmin = false, renderItemInfo = false) {
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
                options += '<div class="review-footer-option"><a href="javascript:void(0);" onclick="window.vue.reportReview(' + elem.id + ');">Report</a>&nbsp;</div>';
            }

            if ((isAdmin) || (elem.userData.id == user)) {
                options += '<div class="review-footer-option"><a href="javascript:void(0);" onclick="window.vue.deleteReview(' + elem.id + ');">Delete</a>&nbsp;</div>';
            }

            let itemInfo = '';
            if (renderItemInfo) {
                itemInfo = '<div class="review-iteminfo"><a href="'  + window.location.origin + '/view/' + elem.framework.slug + '">' + elem.framework.name + '</a></div>';
            }

            let html = `
                <div class="review">
                    <div class="review-header">
                        <div class="review-header-left">
                            <img class="avatar" src="` + window.location.origin + '/gfx/avatars/' + elem.userData.avatar + `" width="64" height="64">
                        </div>

                        <div class="review-header-right">
                            <div class="review-header-right-username"><a href="` + window.location.origin + '/user/' + elem.userData.username + `">` + elem.userData.username + `</a></div>
                            
                            <div class="review-header-right-stars">` + stars + `</div>
                        </div>
                    </div>

                    ` + itemInfo + `

                    <div class="review-content is-wrap">` + elem.content + `</div>

                    <div class="review-footer">
                        ` + options + `
                    </div>
                </div>
            `;

            return html;
        },

        renderNotification: function(elem, newItem = false) {
            let icon = 'fas fa-info-circle';
            let color = 'is-notification-color-black';
            if (elem.type === 'PUSH_WELCOME') {
                icon = 'fas fa-gift';
                color = 'is-notification-color-blue';
            } else if (elem.type === 'PUSH_APPROVAL') {
                icon = 'far fa-check-circle';
                color = 'is-notification-color-green';
            } else if (elem.type === 'PUSH_REVIEWED') {
                icon = 'fas fa-star';
                color = 'is-notification-color-yellow';
            }

            let html = `
                <div class="notification-item ` + ((newItem) ? 'is-new-notification' : '') + `" id="notification-item-` + elem.id + `">
                    <div class="notification-icon">
                        <div class="notification-item-icon"><i class="` + icon + ` fa-3x ` + color + `"></i></div>
                    </div>
                    <div class="notification-info">
                        <div class="notification-item-message">` + elem.longMsg + `</div>
                        <div class="notification-item-message is-color-grey is-font-size-small is-margin-top-5">` + elem.diffForHumans + `</div>
                    </div>
                </div>
            `;

            return html;
        },

        reportUser: function(id) {
            window.vue.ajaxRequest('get', window.location.origin + '/user/' + id + '/report', {}, function(response){
                alert(response.msg);
            });
        },

        reportFramework: function(id) {
            window.vue.ajaxRequest('get', window.location.origin + '/framework/' + id + '/report', {}, function(response){
                alert(response.msg);
            });
        },

        deleteFramework: function(id) {
            if (!confirm(window.vue.translationTable.confirmDeleteFramework)) {
                return;
            }

            window.vue.ajaxRequest('get', window.location.origin + '/framework/' + id + '/delete', {}, function(response){
                alert(response.msg);
            });
        },

        reportReview: function(id) {
            window.vue.ajaxRequest('get', window.location.origin + '/review/' + id + '/report', {}, function(response){
                alert(response.msg);
            });
        },

        deleteReview: function(id) {
            if (!confirm(window.vue.translationTable.confirmDeleteReview)) {
                return;
            }

            window.vue.ajaxRequest('get', window.location.origin + '/review/' + id + '/delete', {}, function(response){
                alert(response.msg);
            });
        },

        setRating: function(value) {
            for (let i = 0; i < 5; i++) {
                document.getElementById('review_rating_star_' + (i + 1).toString()).classList.add('far');
                document.getElementById('review_rating_star_' + (i + 1).toString()).classList.remove('fas');
            }

            for (let i = 0; i < value; i++) {
                document.getElementById('review_rating_star_' + (i + 1).toString()).classList.remove('far');
                document.getElementById('review_rating_star_' + (i + 1).toString()).classList.add('fas');
            }

            document.getElementById('rating').value = value;
        },

        deleteAccount: function() {
            let pw = prompt(window.vue.translationTable.confirmDeleteAccount);
            if ((pw === null) || (pw.length === 0)) {
                return;
            }

            window.vue.ajaxRequest('post', window.location.origin + '/user/account/delete', { password: pw }, function(response){
                alert(response.msg);

                if (response.code == 200) {
                    location.href = window.location.origin;
                }
            });
        }
    }
});