var canvas = '';
var font_15 = 15;
var font_20 = 20;
var font_25 = 25;
$(function(){
    canvas = new Canvas();
    canvas.bg_img_tl();
    
    $("#button_start").click(function(){
        canvas.game_s = true;
        canvas.water(this);
        $("#game_windos").fadeOut();
        $("#game_start").fadeOut();
        $("#help_text").fadeOut();
        $("#start").click();
    })
    $("#button_help").click(function(){
        $("#help_text").removeClass('hide');
        $("#game_start").css('height', '400px');
        $("#start_up").css('height', '15%');
        $("#start_botton").css('height', '15%');
        canvas.water(this);
    })
    $("#button_name").click(function(){
        canvas.water(this);
        $("#game_end").hide();
        $("#game_count").fadeIn();
        $.ajax({
            url: "php/register.php",
            type: "POST",
            data:{
                name: $("#name").val(),
                score: canvas.score,
                time: canvas.all_time
            },
            success: function(data) {
                $('#table tr:not(:first)').remove();
                var data = JSON.parse(data);
                $.each(data, function(key, val){
                    var table = "<tr type='"+val[0]+"'><td>"+parseInt(key+1)+"</td><td>"+val[1]+"</td><td>"+val[2]+"</td><td>"+val[3]+"</td></tr>";
                    $("#table").append(table);
                });
            }
        })
    })
    $("#button_restart").click(function(){
        canvas = new Canvas();
        canvas.bg_img_tl();
        canvas.water(this);

        $("#aubio1").get(0).load();
        $("#canvas img:not(:first)").remove();
        $("#name").val('');
        $("#game_count").fadeOut();
        $("#game_windos").fadeOut();
        $("#button_start").click();
    })

    $("#start").click(function(){
        if(canvas.game_s){
            $(this).addClass('hide');
            $('#stop').removeClass('hide');
            $("#audio1").get(0).play();
            canvas.start_stop = true;
            canvas.game_start();
        }
    })
    $("#stop").click(function(){
        if(canvas.game_s){
            $(this).addClass('hide');
            $('#start').removeClass('hide');
            $("#audio1").get(0).pause();
            canvas.game_stop();
        }
    })

    $("#sound_on").click(function(){
        $(this).addClass('hide');
        $('#sound_off').removeClass('hide');
        $("#audio1").get(0).volume = 0;
    })
    $("#sound_off").click(function(){
        $(this).addClass('hide');
        $('#sound_on').removeClass('hide');
        $("#audio1").get(0).volume = 1;
    })

    $("#font_up").click(function(){
        if(font_15-1 < 10){
            font_15 = 10;
        }else{
            font_15 -= 1;
        }
        if(font_20-1 < 15){
            font_20 = 15;
        }else{
            font_20 -= 1;
        }
        if(font_25-1 < 20){
            font_25 = 20;
        }else{
            font_25 -= 1;
        }
        $(".font_15").css('font-size', font_15+"px");
        $(".font_20").css('font-size', font_20+"px");
        $(".font_25").css('font-size', font_25+"px");
    })
    $("#font_down").click(function(){
        if(font_15+1 > 20){
            font_15 = 20;
        }else{
            font_15 += 1;
        }
        if(font_20+1 > 25){
            font_20 = 25;
        }else{
            font_20 += 1;
        }
        if(font_25+1 > 30){
            font_25 = 30;
        }else{
            font_25 += 1;
        }
        $(".font_15").css('font-size', font_15+"px");
        $(".font_20").css('font-size', font_20+"px");
        $(".font_25").css('font-size', font_25+"px");
    })

    $("body").keydown(function(e){
        if(canvas.game_s && canvas.start_stop && canvas.keydoen && e.keyCode == 32){
            canvas.keydoen = false;
            canvas.attack_add(1);
            canvas.audio_add(1);
        }if(canvas.game_s && canvas.keydoen && e.keyCode == 80){
            canvas.keydoen = false;
            if(canvas.start_stop){
                $("#stop").click();
            }else{;
                $("#start").click();
            }
        }
    }).keyup(function(e){
        if(e.keyCode == 80 || e.keyCode == 32){
            canvas.keydoen = true;
        }
        if(! canvas.game_s){
            if($("#name").val() == ''){ 
                $("#button_name").addClass('button_not_click');
                $("#button_name").removeClass('button_change_color');
            }else{
                $("#button_name").removeClass('button_not_click');
                $("#button_name").addClass('button_change_color');
            }
        }
    })

    $("#rock_fire").click(function(){
        canvas.attack_add(1);
        canvas.audio_add(1);
    })
    $("#rock_move").on('mousemove', function(e){
        var ox = $(this).offset().left + 60;
        var oy = $(this).offset().top + 60;
        var x = (e.pageX-ox)/10;
        var y = (e.pageY-oy)/10;

        if(x > 0){
            x += canvas.label/2;
        }else{
            x -= canvas.label/2;
        }if(y > 0){
            y += canvas.label/2;
        }else{
            y -= canvas.label/2;
        }

        canvas.x = x;
        canvas.y = y;
    }).on('mouseleave', function(){
        canvas.x = 0;
        canvas.y = 0;
    })
})

function Canvas(){
    this.x = 0;
    this.y = 0;

    this.score = 0;
    this.label = 0;

    this.time = '';
    this.tt = 0;
    this.all_time = 0;
    this.shape_time = 60;
    this.attack_time = 180;
    this.supply_time = 300;

    this.game_s = false;
    this.start_stop = false;
    this.keydoen = true;

    var shape = new Shape($(".shape0"), 'shape0', 15, 300, 0, 100, 50);
    this.shape0 = shape;
    this.shape1 = [];
    this.shape2 = [];
    this.shape3 = [];
    this.attack1 = [];
    this.attack2 = [];
    this.supply = [];

    this.bg = 0;
    this.bg_img = [];
}
function Shape(obj, name, power, top, left, width, height){
    this.obj = obj || '';
    this.name = name || '';
    this.power = power || 0;
    this.delete = 0;

    this.top = top || 0;
    this.left = left || 0;

    this.width = width || 0;
    this.height = height || 0;
}

Shape.prototype.draw = function(){
    switch(this.name){
        case"shape0":
            if(this.left + canvas.x > 0 && this.left + canvas.x+this.width < 960){
                this.left += canvas.x;
            }
            if(this.top + canvas.y > 0 && this.top + canvas.y+this.height < 600){
                this.top += canvas.y;
            }
        break
        case"shape1":
            this.left = this.left - 2.8 - canvas.label;
        break
        case"shape2":
            this.left = this.left - 2.6 - canvas.label;
        break
        case"shape3":
            this.left = this.left - 2.4 - canvas.label;
        break
        case"attack1":
            this.left = this.left + 5 + canvas.label;
        break
        case"attack2":
            this.left = this.left - 5 - canvas.label;
        break
        case"supply":
            this.top = this.top + 2.5 + canvas.label;
        break
    }
    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.shape_add = function(type){
    $("#canvas").append('<img src="image/shape'+type+'1.png" class="img shape'+type+' center">');
    this.obj = $('.shape'+type+':last');
    this.name = 'shape'+type;
    this.width = 80;
    this.height = 80;
    this.left = 960;
    this.top = canvas.random(20, 500);

    if(type == 1){
        this.power = 15;
        canvas.shape1.push(this);
    }else if(type == 2){
        this.power = 15;
        canvas.shape2.push(this);
    }else if(type == 3){
        this.power = 30;
        canvas.shape3.push(this);
    }

    $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.attack_add = function(obj, type){
    $("#canvas").append('<img src="image/attack'+type+'.png" class="img attack'+type+' center">');
    this.obj = $('.attack'+type+':last');
    this.name = 'attack'+type;
    this.width = 40;
    this.height = 3;
    this.top = obj.top + (obj.height/2) ;
    
    if(type == 1){
        this.left = obj.left + 100;
        canvas.attack1.push(this);
    }else if(type == 2){
        this.left = obj.left - 40;
        canvas.attack2.push(this);
    }

   $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}
Shape.prototype.supply_add = function(){
    $("#canvas").append('<img src="image/supply.png" class="img supply center">');
    this.obj = $(".supply:last");
    this.name = 'supply';
    this.width = 80;
    this.height = 80;
    this.top = -80;
    this.left = canvas.random(20, 860);
    
    canvas.supply.push(this);
   $(this.obj).css('top', this.top);
    $(this.obj).css('left', this.left);
}


Canvas.prototype.shape_add = function(){
    var time = (240-this.label*20);
    if(time < 100){
        time = 100;
    }
    this.shape_time += time;
    for(i = 0 ; i <= this.random(2,3) ; i++){
        var type = this.random(1,3);
        var shape = new Shape();
        shape.shape_add(type);
    }
}
Canvas.prototype.attack_add = function(type){
    if(type == 1){
        var shape = new Shape();
        shape.attack_add(this.shape0, 1);
    }else if(type == 2){
        var time = (180-this.label*20);
        if(time < 50){
            time = 50;
        }
        this.attack_time += time;
        for(i = 0 ; i < this.shape1.length ; i++){
            var shape = new Shape();
            shape.attack_add(this.shape1[i], 2);
        }
    }
}
Canvas.prototype.supply_add = function(){
    var time = (420-this.label*20);
    if(time < 120){
        time = 120;
    }
    this.supply_time += time;
    var shape = new Shape();
    shape.supply_add();
}
Canvas.prototype.audio_add = function(type){
    if(type == 1){
        $("#game_audio").append('<audio src="sound/shoot.mp3" class="audio1" autoplay></audio>');
    }else if(type == 2){
        $("#game_audio").append('<audio src="sound/destroyed.mp3" class="audio2" autoplay></audio>');
    }
    window.setTimeout(function(){
        $(".audio"+type+":first").remove();
    },500)
}


Canvas.prototype.touch = function(){
    var shape0 = this.shape0;

    for(i = 0 ; i < this.shape1.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)-(this.shape1[i].left+(this.shape1[i].width/2)))) < (shape0.width/2)+(this.shape1[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)-(this.shape1[i].top+(this.shape1[i].height/2)))) < (shape0.height/2)+(this.shape1[i].height/2)
        ){
            shape0.power -= 15;
            $(this.shape1[i].obj).remove();
            this.shape1.splice(i, 1);
            this.audio_add(2);
            this.over();
            return false;
        }
    }

    for(i = 0 ; i < this.shape2.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)-(this.shape2[i].left+(this.shape2[i].width/2)))) < (shape0.width/2)+(this.shape2[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)-(this.shape2[i].top+(this.shape2[i].height/2)))) < (shape0.height/2)+(this.shape2[i].height/2)
        ){
            shape0.power -= 15;
            $(this.shape2[i].obj).remove();
            this.shape2.splice(i, 1);
            this.audio_add(2);
            this.over();
            return false;
        }
    }

    for(i = 0 ; i < this.shape3.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)-(this.shape3[i].left+(this.shape3[i].width/2)))) < (shape0.width/2)+(this.shape3[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)-(this.shape3[i].top+(this.shape3[i].height/2)))) < (shape0.height/2)+(this.shape3[i].height/2)
        ){
            shape0.power -= 15;
            $(this.shape3[i].obj).remove();
            this.shape3.splice(i, 1);
            this.audio_add(2);
            this.over();
            return false;
        }
    }

    for(i = 0 ; i < this.attack2.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)-(this.attack2[i].left+(this.attack2[i].width/2)))) < (shape0.width/2)+(this.attack2[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)-(this.attack2[i].top+(this.attack2[i].height/2)))) < (shape0.height/2)+(this.attack2[i].height/2)
        ){
            shape0.power -= 15;
            $(this.attack2[i].obj).remove();
            this.attack2.splice(i, 1);
            this.audio_add(2);
            this.over();
            return false;
        }
    }

    for(i = 0 ; i < this.supply.length ; i++){
        if(
            Math.abs((shape0.left+(shape0.width/2)-(this.supply[i].left+(this.supply[i].width/2)))) < (shape0.width/2)+(this.supply[i].width/2) &&
            Math.abs((shape0.top+(shape0.height/2)-(this.supply[i].top+(this.supply[i].height/2)))) < (shape0.height/2)+(this.supply[i].height/2)
        ){
            shape0.power += 15;
            $(this.supply[i].obj).remove();
            this.supply.splice(i, 1);
            return false;
        }
    }

    for(j = 0 ; j < this.attack1.length ; j++){
        var attack1 = this.attack1[j];

        for(i = 0 ; i < this.shape1.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)-(this.shape1[i].left+(this.shape1[i].width/2)))) < (attack1.width/2)+(this.shape1[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)-(this.shape1[i].top+(this.shape1[i].height/2)))) < (attack1.height/2)+(this.shape1[i].height/2)
            ){
                $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape1[i].top+'px; left: '+this.shape1[i].left+'px"></div>');
                this.shape1[i].power -= 15;

                this.score += 5;

                $(attack1.obj).remove();
                this.attack1.splice(j, 1);

                return false;
            }
        }

        for(i = 0 ; i < this.shape2.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)-(this.shape2[i].left+(this.shape2[i].width/2)))) < (attack1.width/2)+(this.shape2[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)-(this.shape2[i].top+(this.shape2[i].height/2)))) < (attack1.height/2)+(this.shape2[i].height/2)
            ){
                $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape2[i].top+'px; left: '+this.shape2[i].left+'px"></div>');
                this.shape2[i].power -= 15;
                this.score -= 10;
                $(attack1.obj).remove();
                this.attack1.splice(j, 1);
                return false;
            }
        }

        for(i = 0 ; i < this.shape3.length ; i++){
            if(
                Math.abs((attack1.left+(attack1.width/2)-(this.shape3[i].left+(this.shape3[i].width/2)))) < (attack1.width/2)+(this.shape3[i].width/2) &&
                Math.abs((attack1.top+(attack1.height/2)-(this.shape3[i].top+(this.shape3[i].height/2)))) < (attack1.height/2)+(this.shape3[i].height/2)
            ){
                $(attack1.obj).remove();
                this.attack1.splice(j, 1);
                this.shape3[i].power -= 15;

                if(this.shape3[i].power == 15){
                    $(this.shape3[i].obj).attr('src', 'image/shape32.png');
                }else{
                    this.shape3[i].power = 0;
                    this.score += 10;
                    $("#canvas").append('<div class="touch" style="position: absolute; width: 80px; height: 80px; top: '+this.shape3[i].top+'px; left: '+this.shape3[i].left+'px"></div>');
                }
                return false;
            }
        }
    }
}


Canvas.prototype.ready_remove = function(){
    for(i = 0 ; i < this.shape1.length ; i++){
        if((this.shape1[i].power == 0 || this.shape1[i].left < -80) && this.shape1[i].delete < 100){
            this.shape1[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){
        if((this.shape2[i].power == 0 || this.shape2[i].left < -80) && this.shape2[i].delete < 100){
            this.shape2[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){
        if((this.shape3[i].power == 0 || this.shape3[i].left < -80) && this.shape3[i].delete < 100){
            this.shape3[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){
        if(this.attack1[i].left > 960){
            this.attack1[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.attack2.length ; i++){
        if(this.attack2[i].left < -40){
            this.attack2[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.supply.length ; i++){
        if(this.supply[i].left > 600){
            this.supply[i].delete += 1;
        }
    }
}
Canvas.prototype.remove = function(){
    for(i = 0 ; i < this.shape1.length ; i++){
        if(this.shape1[i].delete >= 10){
            $('.touch').remove();
            $(this.shape1[i].obj).remove();
            this.shape1.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){
        if(this.shape2[i].delete >= 5){
            $('.touch').remove();
            $(this.shape2[i].obj).remove();
            this.shape2.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){
        if(this.shape3[i].delete >= 5){
            $('.touch').remove();
            $(this.shape3[i].obj).remove();
            this.shape3.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){
        if(this.attack1[i].delete >= 5){
            $(this.attack1[i].obj).remove();
            this.attack1.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.attack2.length ; i++){
        if(this.attack2[i].delete >= 5){
            $(this.attack2[i].obj).remove();
            this.attack2.splice(i, 1);
            return false;
        }
    }
    for(i = 0 ; i < this.supply.length ; i++){
        if(this.supply[i].delete >= 5){
            $(this.supply[i].obj).remove();
            this.supply.splice(i, 1);
            return false;
        }
    }
}
Canvas.prototype.draw = function(){
    for(i = 0 ; i < this.shape1.length ; i++){
        if(this.shape1[i].delete == 0){
            this.shape1[i].draw();
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){
        if(this.shape2[i].delete == 0){
            this.shape2[i].draw();
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){
        if(this.shape3[i].delete == 0){
            this.shape3[i].draw();
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){
        if(this.attack1[i].delete == 0){
            this.attack1[i].draw();
        }
    }
    for(i = 0 ; i < this.attack2.length ; i++){
        if(this.attack2[i].delete == 0){
            this.attack2[i].draw();
        }
    }
    for(i = 0 ; i < this.supply.length ; i++){
        if(this.supply[i].delete == 0){
            this.supply[i].draw();
        }
    }
    this.shape0.draw();
}


Canvas.prototype.game_start = function(){
    canvas.tt ++;

    if(canvas.tt % 60 == 0){
        canvas.all_time ++;
        canvas.shape0.power -= 1;
    }if(canvas.tt % 300 == 0){
        canvas.label += 0.5;
    }if(canvas.tt == canvas.shape_time){
        canvas.shape_add();
    }if(canvas.tt == canvas.supply_time){
        canvas.supply_add();
    }if(canvas.tt == canvas.attack_time){
        canvas.attack_add(2);
    }

    canvas.touch();
    canvas.ready_remove();
    canvas.remove();

    canvas.controller_change();
    canvas.draw();
    canvas.game_end();
    if(canvas.game_s){
        canvas.time = requestAnimationFrame(canvas.game_start);
    }
}
Canvas.prototype.game_stop = function(){
    this.start_stop = false;
    cancelAnimationFrame(this.time);
}
Canvas.prototype.game_end = function(){
    if(this.shape0.power <= 0){
        $("#stop").click();
        this.game_s = false;
        $("#game_windos").show();
        $("#game_end").show();
    }
}


Canvas.prototype.controller_change = function(){
    if(this.shape0.power <= 0){
        this.shape0.power = 0;
    }if(this.shape0.power >= 30){
        this.shape0.power = 30;
    }
    $("#score").text(this.score);
    $("#time").text(this.all_time+"s");
    $("#power").text(this.shape0.power);
    $("#power_color").css('height', parseInt(this.shape0.power/30*100));
}
Canvas.prototype.bg_img_tl = function(){
    for(i = 1 ; i <= 6 ; i ++){
        var top = this.random(40*i, 40*i+40);
        var left = this.random(70*i, 70*i+70);

        $("#bg_img"+i).css('top', top);
        $("#bg_img"+i).css('left', left);
    }
}
Canvas.prototype.over = function(){
    $("#game").addClass('over');
    window.setTimeout(function(){
        $("#game").removeClass('over');
    },250)
}
Canvas.prototype.water = function(obj){
    $(".water").remove();
    $(obj).append('<div class="water"></div>');
    $(".water").css('height', $(obj).width());
    $(".water").css('width', $(obj).width());
}
Canvas.prototype.random = function(min, max){
    return parseInt(Math.random()*max)+min;
}