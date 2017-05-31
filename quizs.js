(function($) {
    $.fn.jquizzy = function(settings) {
        var defaults = {
            questions: null,
            startImg: 'images/start.gif',
            endText: '已结束!',
            sendResultsURL: null,
            /*
            resultComments: {
                perfect: '你是爱因斯坦么?',
                excellent: '非常优秀!',
                good: '很好，发挥不错!',
                average: '一般般了。',
                bad: '太可怜了！',
                poor: '好可怕啊！',
                worst: '悲痛欲绝！'
            }
            */
        };

        var config = $.extend(defaults, settings);
        if (config.questions === null) {
            $(this).html('<div class="intro-container slide-container"><h2 class="qTitle">Failed to parse questions.</h2></div>');
            return;
        }

        var superContainer = $(this),
        introFob = '<div class="intro-container slide-container"><a class="nav-start" href="#" id="start_answer">请认真完成测试题。准备好了吗？<br/><br/><span><img src="'+config.startImg+'"></span></a></div>	',
        exitFob = '<div class="results-container slide-container"><div class="question-number">' + config.endText + '</div><div class="result-keeper"></div></div><div class="notice">请选择一个选项！</div><div class="progress-keeper" ><div class="progress"></div></div>',
        contentFob = '',
        questionid = [],
        i,
        sid,
        questionsIteratorIndex,
        answersIteratorIndex;
        superContainer.addClass('main-quiz-holder');
        //config.questions.length
        for (questionsIteratorIndex = 0; questionsIteratorIndex < config.questions.length; questionsIteratorIndex += 10) {
           // contentFob += '<div class="slide-container"><div class="question-number">' + (questionsIteratorIndex + 1) + '/' + config.questions.length + '</div><div class="question">' + (questionsIteratorIndex+1) + '、' +config.questions[questionsIteratorIndex].question + '</div><ul class="answers">';
            contentFob+='<div class="slide-container">';
            for(i = questionsIteratorIndex; i < (questionsIteratorIndex+10) && i < config.questions.length; i++)
            {
                console.log('i='+i);
                console.log('length='+config.questions.length);
                contentFob += '<div class="question">' + (i+1) + '、' + config.questions[i].question + '</div><ul class="answers">';
                questionid.push(config.questions[i].qid);
                for (answersIteratorIndex = 0; answersIteratorIndex < config.questions[i].answers.length; answersIteratorIndex++) {
                    contentFob += '<li>' + config.questions[i].answers[answersIteratorIndex] + '</li>';
                }
            
                contentFob += '</ul>';
            }
            contentFob += '<div class="nav-container">';
            if (questionsIteratorIndex !== 0) {
                contentFob += '<div class="prev"><a class="nav-previous" href="#">&lt; 上一页</a></div>';
            }
            if (questionsIteratorIndex < config.questions.length - 10) {
                contentFob += '<div class="next"><a class="nav-next" href="#">下一页 &gt;</a></div>';
            } else {
                contentFob += '<div class="next final"><a class="nav-show-result" href="#">完成</a></div>';
            }
            contentFob += '</div></div></div>';
            //questionid.push(config.questions[questionsIteratorIndex].qid);
        }

        sid = config.questions[0].sid;

        superContainer.html(introFob + contentFob + exitFob);

        var progress = superContainer.find('.progress'),
        progressKeeper = superContainer.find('.progress-keeper'),
        notice = superContainer.find('.notice'),
        progressWidth = progressKeeper.width(),
        userAnswers = [],
        questionLength = config.questions.length,
        slidesList = superContainer.find('.slide-container');

        function roundReloaded(num, dec) {
            var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
            return result;
        }

        /*
        function judgeSkills(score) {
            var returnString;
            if (score === 100) return config.resultComments.perfect;
            else if (score > 90) return config.resultComments.excellent;
            else if (score > 70) return config.resultComments.good;
            else if (score > 50) return config.resultComments.average;
            else if (score > 35) return config.resultComments.bad;
            else if (score > 20) return config.resultComments.poor;
            else return config.resultComments.worst;
        }
        */

        progressKeeper.hide();
        notice.hide();
        slidesList.hide().first().fadeIn(500);
        superContainer.find('li').click(function() {
            var thisLi = $(this);
            if (thisLi.hasClass('selected')) {
                thisLi.removeClass('selected');
            }
            else {
                //thisLi.parents('.answers').children('li').removeClass('selected');
                thisLi.addClass('selected');
            }

        });

        superContainer.find('.nav-start').click(function() {
            $(this).parents('.slide-container').fadeOut(500,
            function() {
                $(this).next().fadeIn(500);
                progressKeeper.fadeIn(500);
            });
            return false;
        });

        superContainer.find('.next').click(function() {
            /*
            if ($(this).parents('.slide-container').find('li.selected').length === 0) {
                notice.fadeIn(300);
                return false;
            }
            */
            notice.hide();
            $(this).parents('.slide-container').fadeOut(500,
            function() {
                $(this).next('.slide-container').fadeIn(500);
            });
            progress.animate({
                width: progress.width() + Math.round(progressWidth / questionLength)*10
            },
            500);
            return false;
        });

        superContainer.find('.prev').click(function() {
            notice.hide();
            $(this).parents('.slide-container').fadeOut(500,
            function() {
                $(this).prev().fadeIn(500);
            });
            progress.animate({
                width: progress.width() - Math.round(progressWidth / questionLength)*10
            },
            500);
            return false;
        });
        
        superContainer.find('.final').click(function() {
            /*
            if ($(this).parents('.slide-container').find('li.selected').length === 0) {
                notice.fadeIn(300);
                return false;
            }
            */
            superContainer.find('li').each(function(index) {
                if ($(this).hasClass('selected'))
                    userAnswers.push(1);
                else
                    userAnswers.push(0);
                //userAnswers.push($(this).parents('.answers').children('li').index($(this).parents('.answers').find('li.selected')) + 1);
            });
			
			progressKeeper.hide();
			//var resultSet = '';
			
            if (config.sendResultsURL !== null) {
                var collate = [];
				var myanswers = '{' + '"sid": ' + sid + ',' + '"cid": ' + 1 + ',' + '"submit": [';
                for (r = 0; r < userAnswers.length; r+=4) {
                    collate.push('{"qid":' + questionid[r/4] + ', "choiceA":' + userAnswers[r] + ', "choiceB":' + userAnswers[r+1] + ', "choiceC":' + userAnswers[r+2] + ', "choiceD":' + userAnswers[r+3] + '}');
					//myanswers = myanswers + userAnswers[r]+'|';  
                }
                myanswers = myanswers + collate + ']' + '}';
                //document.writeln(myanswers);

				
				$.getJSON(config.sendResultsURL,{test:myanswers},function(json){
					if (json==null) {
						alert('通讯失败！');
					} else {
                        
                        alert('您已经完成考试'+', 您的得分是：' + json);
                        /*
						var score = json['score'];
						$.each(corrects,function(index,array){
							resultSet += '<div class="result-row">' + (corrects[index] === 1 ? "<div class='correct'>#"+(index + 1)+"<span></span></div>": "<div class='wrong'>#"+(index + 1)+"<span></span></div>")+'</div>';
						});
                        */
						//resultSet = '<h2 class="qTitle">' + '您已完成考试！' + '</br> 您的分数： ' + '</h2>' ;
						//alert(json);
                        //var resultSet = '<div>1</div>';
						//superContainer.find('.result-keeper').html(resultSet).show(500);
                        
					}	
				});
            }
            
            
            $(this).parents('.slide-container').fadeOut(500,
            function() {
                $(this).next().fadeIn(500);
            });
            return false;
        });
    };
})(jQuery);