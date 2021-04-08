$(document).ready(function ($) {

    var $loading = $('#sk-chase').hide();
    $(document)
        .ajaxStart(function () {
            $loading.show();
        })
        .ajaxStop(function () {
            $loading.hide();
        });

    $('.ckeditor_textarea').each(function(e){
        CKEDITOR.replace(this.id,
            {
                toolbar: 'Common'
            });
    });
    var resultContainer = $('#quoteList .quote_number');
    var followerContainer = $('#followers .followers_number');
    var followingToggle = $('#following');

    var service = {
        ajaxCall: function (url) {
            $.ajax({
                url: url,
                cache: false,
                data: {url: url},
                success: function (result) {
                    if (result.quote) {
                        quoteFunction(result, resultContainer);
                    }
                    else if (result.toggleFollower) {
                        followerFunction(result, followerContainer, followingToggle)
                    }
                    else if (result.reaction) {
                        reactionFunction(result);
                    }
                    else if (result.vote) {
                        voteFunction(result);
                    }
                    else if (result.reactionUsers) {
                        userReactionListFunction(result);
                    }
                },
                error: function (jqXHR, textStatus, errorThrow) {
                    resultContainer.html('Ajax request - ' + textStatus + ': ' + errorThrow).fadeIn('fast');
                }
            });
        }
    };

    var urlCount=$('.getAjaxOnLoad').attr('href');
    service.ajaxCall(urlCount);
    resultContainer.fadeOut('fast');

    $(document).on('click', '.quote', function (ev) {
        var url=$(this).attr('href');
        ev.preventDefault();
        service.ajaxCall(url);
        resultContainer.fadeOut('fast');

        if($(this).hasClass('noticed')) {
            $(this).removeClass('noticed');
        } else {
            $(this).addClass('noticed');
        }
    });
    $(document).on('click', '.removeQuotes', function (ev) {
        var urlRemove=$(this).attr('href');
        ev.preventDefault();
        service.ajaxCall(urlRemove);
    });

    $(document).on('click', '.followers_results', function (ev) {
        var followToggle=$(this).attr('href');
        console.log(followToggle)
        ev.preventDefault();
        service.ajaxCall(followToggle);
    });

    $(document).on('click', '.reaction a', function (ev) {
        var reactionUrl=$(this).attr('href');
        ev.preventDefault();
        service.ajaxCall(reactionUrl);
    });

    $(document).on('click', '.vote', function (ev) {
        var vote=$(this).attr('href');
        ev.preventDefault();
        service.ajaxCall(vote);
    });

    $(document).on('click', '.showUsers', function (ev) {
        var userToBeFound=$(this).attr('href');
        ev.preventDefault();
        service.ajaxCall(userToBeFound);
    });

});

startTimer();
$(window).focus(function(e) {
    startTimer();
});
$(window).blur(function(e) {
    stopTimer();
});

var count = 0;
var myInterval;

function timerHandler() {
    count++;
    document.getElementById("seconds").innerHTML = count;
}

// Start timer
function startTimer() {
    myInterval = window.setInterval(timerHandler, 1000);
}

// Stop timer
function stopTimer() {
    window.clearInterval(myInterval);
}

function userReactionListFunction(result) {
    var reactionListContainer = $('#showUsers .reaction-content');
    reactionListContainer.html(result.reactionUsers);
    $('#showUsers').modal('show')
}

function quoteFunction(result, resultContainer) {
    resultContainer.html(result.quote.count).fadeIn('fast');
    if(result.quote.count > 0) {
        resultContainer.addClass('visible');
    } else {
        resultContainer.removeClass('visible');
    }
}
function followerFunction(result, container, toggle) {
    container.html(result.toggleFollower.count).fadeIn('fast');
    if (result.toggleFollower.action === 'added')
    {
        toggle.removeClass('d-none');
    }
    else {
        toggle.addClass('d-none');
    }
}
function voteFunction(result) {
    var reputationContainer = $('.reputation.user-'+ result.vote.userId);
    var container = $('#'+result.vote.object+'-'+result.vote.objectUid+' .vote_results');
    var upVoteSign = $('#'+result.vote.object+'-'+result.vote.objectUid+' .votes .upvote');
    var downVoteSign = $('#'+result.vote.object+'-'+result.vote.objectUid+' .votes .downvote');

    if (result.vote.message) {
        container.html(result.vote.message).fadeIn('fast');
    }
    else {
        container.html(result.vote.newResults).fadeIn('fast');
    }
    reputationContainer.html(result.vote.userReputation).fadeIn('fast');

    if (result.vote.status === 'no-vote')
    {
        if (downVoteSign.hasClass('voted'))
        {
            downVoteSign.removeClass('voted');
        }
        else if (upVoteSign.hasClass('voted')) {
            upVoteSign.removeClass('voted');
        }
    }
    else if (result.vote.status === 'upVoted') {
        if (downVoteSign.hasClass('voted'))
        {
            downVoteSign.removeClass('voted');
            upVoteSign.addClass('voted');
        }
        else {
            upVoteSign.addClass('voted');
        }
    }
    else if (result.vote.status === 'downVoted') {
        if (upVoteSign.hasClass('voted'))
        {
            upVoteSign.removeClass('voted');
            downVoteSign.addClass('voted');
        }
        else {
            downVoteSign.addClass('voted');
        }
    }
}
function reactionFunction(result) {
    var reputationContainer = $('.reputation.user-'+ result.reaction.userId);
    reputationContainer.html(result.reaction.userReputation).fadeIn('fast');

    var reactionContainer =
        $('#'+result.reaction.object+'-'+result.reaction.objectUid+' .reaction-'+result.reaction.reactionUid+' .counter');
    var lastReaction =
        $('#'+result.reaction.object+'-'+result.reaction.objectUid+' .reaction-'+result.reaction.previousReaction+' .counter');

    console.log('#'+result.reaction.object+'-'+result.reaction.objectUid);
    console.log(lastReaction);

    var oldValue = +reactionContainer.text();
    var oldReactionValue = +lastReaction.text();

    if (result.reaction.action === 'removed')
    {
        if (+reactionContainer.text() > 0)
        {
            var newRemovedValue = oldValue - 1;
            reactionContainer.html(newRemovedValue);
            reactionContainer.removeClass('chosen');
        }
    }
    else {
        var newAddedValue = oldValue + 1;
        reactionContainer.html(newAddedValue);
        reactionContainer.addClass('chosen');

        if (result.reaction.previousReaction)
        {
            var removeOldReaction = oldReactionValue - 1;
            lastReaction.html(removeOldReaction);
            lastReaction.removeClass('chosen');
        }
    }
}