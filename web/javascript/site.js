var snipy = {
    navigation: function(e)
    {
        var getNavClass = $(e).parent().parent();
        if(getNavClass.hasClass('left-nav-bar-mobile')){
            getNavClass.removeClass('left-nav-bar-mobile');
            localStorage.setItem('navigationMobile', false);
            $('body').css('margin-left','170px');
        }else{
            getNavClass.addClass('left-nav-bar-mobile');
            localStorage.setItem('navigationMobile',true);
            $('body').css('margin-left','42px');
        }
    },
    setPrintedStude: function(cont)
    {
        $(cont).parent().addClass('printed-document-active');
    },
    checkCourse: function(url)
    {
        $('#print_start_course').dialog({
            autoOpen: false,
            resizable: false,
            show: {
                effect: "explode",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            width: "auto",
            modal: true,
            buttons: {  }
        });
        $('#print_start_course').load(url);
        $('#print_start_course').dialog('open');
    },
    checkParams: function(cont, url)
    {
        var NumberOrder = $('#number_order').val();
        var EndDate = $('#endDate').val();
        var predsedatel = $('#predsedatel').val();
        var chlen1 = $('#chlen1').val();
        var chlen2 = $('#chlen2').val();
        var teoriqDate = $('#teoriqDate').val();
        var prakDate = $('#prakDate').val();
        var firma_prak = $('#firma_prak').val();
        var firma_teoriq = $('#firma_teoriq').val();

        var predsedatel1 = $('#predsedatel1').val();
        var chlen11 = $('#chlen11').val();
        var chlen21 = $('#chlen21').val();

        var predsedatel2 = $('#predsedatel2').val();
        var chlen12 = $('#chlen12').val();
        var chlen22 = $('#chlen22').val();

        var predsedatel3 = $('#predsedatel3').val();
        var chlen13 = $('#chlen13').val();
        var chlen23 = $('#chlen23').val();

        var predsedatel4 = $('#predsedatel4').val();
        var chlen14 = $('#chlen14').val();
        var chlen24 = $('#chlen24').val();

        var predsedatel5 = $('#predsedatel5').val();
        var chlen15 = $('#chlen15').val();
        var chlen25 = $('#chlen25').val();


        var createUrl = url;
        createUrl +='&num='+NumberOrder+'&end='+EndDate;

        if(teoriqDate)
            createUrl +='&teoriqDate='+teoriqDate;

        if(firma_prak)
            createUrl +='&firma_prak='+firma_prak;

        if(firma_teoriq)
            createUrl +='&firma_teoriq='+firma_teoriq;

        if(prakDate)
            createUrl +='&prakDate='+prakDate;

        if(predsedatel)
            createUrl +='&predsedatel='+predsedatel;

        if(chlen1)
            createUrl +='&chlen1='+chlen1;

        if(chlen2)
            createUrl +='&chlen2='+chlen2;

        if(predsedatel1)
            createUrl +='&predsedatel1='+predsedatel1;

        if(chlen11)
            createUrl +='&chlen11='+chlen11;

        if(chlen21)
            createUrl +='&chlen21='+chlen21;

        if(predsedatel2)
            createUrl +='&predsedatel2='+predsedatel2;

        if(chlen12)
            createUrl +='&chlen12='+chlen12;

        if(chlen22)
            createUrl +='&chlen22='+chlen22;

        if(predsedatel3)
            createUrl +='&predsedatel3='+predsedatel3;

        if(chlen13)
            createUrl +='&chlen13='+chlen13;

        if(chlen23)
            createUrl +='&chlen23='+chlen23;

        if(predsedatel4)
            createUrl +='&predsedatel4='+predsedatel4;

        if(chlen14)
            createUrl +='&chlen14='+chlen14;

        if(chlen24)
            createUrl +='&chlen24='+chlen24;

        if(predsedatel5)
            createUrl +='&predsedatel5='+predsedatel5;

        if(chlen15)
            createUrl +='&chlen15='+chlen15;

        if(chlen25)
            createUrl +='&chlen25='+chlen25;

        $(cont).attr('href', createUrl);
    },
    checkParamsRam: function(cont, url)
    {
        var NumberOrder = $('#number_order').val();
        var EndDate = $('#endDate').val();
        var zapDate = $('#zapDate').val();
        var NumberGrupa = $('#number_grupa').val();
        var HourseExam = $('#hours_exam').val();
        var TemaExam = $('#tema_exam').val();
        var NumberGrupaReal = $('#number_grupa_real').val();
        var teacherdej = $('#teacherdej').val();

        var predsedatel = $('#predsedatel').val();
        var chlen1 = $('#chlen1').val();
        var chlen2 = $('#chlen2').val();
        var chlen3 = $('#chlen3').val();
        var type = $('#type').val();
        var predmet = $('#predmet').val();


        var createUrl = url;

        if(type)
            createUrl += '&type='+type;

        if(predmet)
            createUrl += '&predmet='+predmet;

        if(NumberOrder)
            createUrl += '&num='+NumberOrder;

        if(EndDate)
            createUrl += '&end='+EndDate;

        if(zapDate)
            createUrl += '&zap='+zapDate;

        if(NumberGrupa)
            createUrl += '&grupa='+NumberGrupa;

        if(HourseExam)
            createUrl += '&hour='+HourseExam;

        if(TemaExam)
            createUrl += '&tema='+TemaExam;

        if(NumberGrupaReal)
            createUrl += '&realgroup='+NumberGrupaReal;

        if(teacherdej)
            createUrl += '&teacherdej='+teacherdej;


        if(predsedatel)
            createUrl +='&predsedatel='+predsedatel;

        if(chlen1)
            createUrl +='&chlen1='+chlen1;

        if(chlen2)
            createUrl +='&chlen2='+chlen2;

        if(chlen3)
            createUrl +='&chlen3='+chlen3;

        //$(cont).attr('href', url+'&num='+NumberOrder+'&end='+EndDate+'&zap='+zapDate+'&grupa='+NumberGrupa+'&hour='+HourseExam+'&tema='+TemaExam);
        $(cont).attr('href', createUrl);
    },
    printAllStudents: function(url)
    {
        $('#print_start_course').dialog({
            autoOpen: false,
            resizable: false,
            show: {
                effect: "explode",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            width: "auto",
            modal: true,
            buttons: {  }
        });
        $('#print_start_course').load(url);
        $('#print_start_course').dialog('open');

    },
    checkNavigation: function()
    {
        var retrievedObject = localStorage.getItem('navigationMobile');
        if(retrievedObject && retrievedObject == "true"){
            $('.custom-nav-bar').addClass('left-nav-bar-mobile');
            $('body').css('margin-left','42px');
        }else{
            $('.custom-nav-bar').removeClass('left-nav-bar-mobile');
            $('body').css('margin-left','170px');
        }
    },
    setActiveNav: function(classActive)
    {
        $('.custom-nav-bar').find('a').removeClass('active');
        $('.'+classActive).addClass('active');
    },
    changeFilter: function(url, show)
    {
        var limit = $('#filter-select-val').val();
        var search = $('.search-input').val();
        var obj = $('#filter-select-val-obj').val();
        if(show){
            $.pjax({url: url, container: '#reload_index_page'});
        }else if(obj){
            $.pjax({url: url+'?limit='+limit+'&search='+search+'&obj='+obj, container: '#reload_index_page'});
        }else{
            $.pjax({url: url+'?limit='+limit+'&search='+search, container: '#reload_index_page'});
        }

    },
    addHidden: function(e)
    {
        $(e).parent().parent().addClass('hidden');
    },
    showEditModal: function(url, group)
    {
        $.ajax({
            url: url,
            success: function(result){
                $(".edit-items-modal").html(result);
                if(group)
                    $(group).modal("show");
                else
                    $("#myModalEdit").modal("show");

        }});
    },
    submitFormNew: function(url)
    {
        $.ajax({
            url: url,
            type: 'post',
            data: $("#form_submit_new").serialize(),
            success: function(result){
                $(".modal-backdrop").remove();
                $(".edit-items-modal").html(result);
                $("#myModalGroup").modal("show");
            }});
    },
    deletePostImage: function(e,div)
    {
        $(e).parent().append(div);
        $(e).remove();
        $('.upload-post-cover').remove();
        $('.featuredImageDeleted').val('1');

    },
    submitFormEdit: function(url)
    {
        $.ajax({
            url: url,
            type: 'post',
            data: $("#form_submit_edit").serialize(),
            success: function(result){
                $(".modal-backdrop").remove();
                $(".edit-items-modal").html(result);
                $("#myModalEdit").modal("show");
        }});
    },
    redirectUrl: function(url)
    {
        window.location.href = url;
    },
    changeFrontendTab: function(e, allTabs, active, oneMoreActive)
    {
        $("."+allTabs).find('li').removeClass("active");
        $(e).parent().addClass("active");
        $("."+allTabs).parent().find('.row').addClass("hidden");
        $("."+active).removeClass("hidden");
        if(oneMoreActive){
            $("."+oneMoreActive).removeClass("hidden");
        }

    },
    hideAmountBonuses: function(cont)
    {
        if($("."+cont).hasClass('hidden')){
            $("."+cont).removeClass('hidden');
        }else{
            $("."+cont).addClass('hidden');
        }
    },
    getDatePickers: function($link, $link2, $link3, $link4, $link5)
    {
        if($link) {
            $('body').on('focus', $link, function () {
                $(this).datetimepicker({
                    format: 'YYYY-MM-DD HH:mm'
                });
            })
        }

        if($link2) {
            $('body').on('focus',$link2, function(){
                $(this).datetimepicker({
                    format:'YYYY-MM-DD HH:mm'
                });
            })
        }

        if($link3) {
            $('body').on('focus',$link3, function(){
                $(this).datetimepicker({
                    format:'YYYY-MM-DD HH:mm'
                });
            })
        }

        if($link4) {
            $('body').on('focus',$link4, function(){
                $(this).datetimepicker({
                    format:'YYYY-MM-DD HH:mm'
                });
            })
        }

        if($link5) {
            $('body').on('focus',$link5, function(){
                $(this).datetimepicker({
                    format:'YYYY-MM-DD HH:mm'
                });
            })
        }
    },
    setFollowingWeekend: function(e)
    {
        var getAttr = $(e).attr("data-counter-date");
        getAttr++;
        $(e).attr("data-counter-date",getAttr);
    },
    checkPreviewGroup: function(htmlForAppend, url, repeat, until, launchDate, expireDate)
    {
        $.ajax({
            url: url,
            type: 'post',
            data: {repeat: $(repeat).val(), until: $(until).val(), launchDate: $(launchDate).val(), expireDate: $(expireDate).val()},
            success: function(result){
                $(htmlForAppend).html(result);
            }
        });
    },
    affilateLink: function(e)
    {
        var getValue = $(e).val();
        if(getValue == "d2"){
            $(".bonuses-direct_affiliate_link").removeClass("hidden");
            $(".bonuses-direct-label").removeClass("hidden");
        }else{
            $(".bonuses-direct_affiliate_link").addClass("hidden");
            $(".bonuses-direct-label").addClass("hidden");
        }
    },
    getChoosenTerms: function(cont, select, input, inputClass)
    {
        var getSelect = $("#"+select).val();
        if(getSelect == null){
            return;
        }
        var getText = $("#"+select+" option:selected").text();
        var textInput = '<div class="term-add-remove-panel" onclick="snipy.removeChoosenTerm(this,'+getSelect+',&quot;'+getText+'&quot;,&quot;'+select+'&quot;)"><input type="hidden" class="'+inputClass+'" name="'+input+'" value="'+getSelect+'">'+getText+' <span class="fa fa-trash trash-red-terms"></span></div>';
        $('.'+cont).append(textInput);
        $('#'+select+' option[value="' + getSelect + '"]').remove();

    },
    removeChoosenTerm: function(cont, val, text, select)
    {
        var getText = '<option value="'+val+'">'+text+'</option>';
        $("#"+select).append(getText);
        $(cont).remove();
    },
    setTermsEdit: function(id)
    {
        if($('#getPostCategories option[value="' + id + '"]').length)
        {
            $('#getPostCategories option[value="' + id + '"]').prop('selected', true)
            snipy.getChoosenTerms('category-post-add-remove','getPostCategories', 'hiddenInputCategory[]', 'hiddenInputCat');
        }

        if($('#getPostTags option[value="' + id + '"]').length)
        {
            $('#getPostTags option[value="' + id + '"]').prop('selected', true)
            snipy.getChoosenTerms('tags-post-add-remove','getPostTags', 'hiddenInputTag[]', 'hiddenInputTag')
        }
    }
};

//need to have exactly the same id's and js variables: cityRelations, cities, communities
function changeRegion() {
    var regionId = $('#region').val();
    var targetCommunitiesIds = Object.keys(cityRelations[regionId]);
    var i = 0;
    $('#community').empty();
    for (i in targetCommunitiesIds) {
        var name = communities[targetCommunitiesIds[i]];
        $('<option value="' + targetCommunitiesIds[i] + '">').text(name).appendTo($('#community'));
    }
    changeCommunity();
}

function changeCommunity(){
    $('#city').empty();
    var regionId = $('#region').val();
    var communityId = $('#community').val();
    var targetCitiesIds = cityRelations[regionId][communityId];
    var i = 0;
    for (i in targetCitiesIds) {
        var name = cities[targetCitiesIds[i]];
        $('<option value="' + targetCitiesIds[i] + '">').text(name).appendTo($('#city'));
    }
}

function changeRegionBirth() {
    var regionId = $('#regionBirth').val();
    var targetCommunitiesIds = Object.keys(cityRelations[regionId]);
    var i = 0;
    $('#communityBirth').empty();
    for (i in targetCommunitiesIds) {
        var name = communities[targetCommunitiesIds[i]];
        $('<option value="' + targetCommunitiesIds[i] + '">').text(name).appendTo($('#communityBirth'));
    }
    changeCommunityBirth();
}

function changeCommunityBirth(){
    $('#cityBirth').empty();
    var regionId = $('#regionBirth').val();
    var communityId = $('#communityBirth').val();
    var targetCitiesIds = cityRelations[regionId][communityId];
    var i = 0;
    for (i in targetCitiesIds) {
        var name = cities[targetCitiesIds[i]];
        $('<option value="' + targetCitiesIds[i] + '">').text(name).appendTo($('#cityBirth'));
    }
}

$(function () {
    $(document).on('change', '#region', changeRegion);
    $(document).on('change', '#community', changeCommunity);
    $(document).on('change', '#regionBirth', changeRegionBirth);
    $(document).on('change', '#communityBirth', changeCommunityBirth);
    $('#region').trigger('change');
    $('#regionBirth').trigger('change');
});