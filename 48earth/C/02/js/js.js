var game_time = "";
var canvas = ""; //Canvas的畫布變數


$(function(){ 
    canvas = new Canvas();
    canvas.draw();
    canvas.power_change();
    
    $("#button_start").click(function(){ // 遊戲開始按鈕
        $("#game_start").fadeOut();
        $("#game_windows").fadeOut();
        $(".restart[type=start]").click();
    })
    $("#button_how").click(function(){ // 遊戲教學按鈕
        var n = 0;
        $("#game_helop").fadeIn();
        $("#game_helop li").each(function(index, element){
            n++;
            setTimeout(function(){
                $(element).fadeIn(300);
            }, n * 500);
        });
        $('#game_start').css('margin-top', "-200px");
    })
    $("#button_restart").click(function(){ //重新開始
        $("#game_windows").fadeOut();
        $("#game_count").fadeOut();
        $("#canvas img:not(:first)").remove();
        $("#audio0").get(0).load();
        game_time = "";
        canvas = new Canvas();
        canvas.draw();
        canvas.power_change();
        $(".restart[type=start]").click();
    })
    
    
    $(".restart").click(function(){ // 開始,暫停
        if(canvas.game_st){
            $(".restart[type=start]").show();
            $(".restart[type=stop]").hide();
            $("#audio0").get(0).pause();
            canvas.game_st = false;
            canvas.game_stop();
        }else{
            $(".restart[type=start]").hide();
            $(".restart[type=stop]").show();
            $("#audio0").get(0).play();
            canvas.game_st = true;
            canvas.game_start();
        }
    })
    $(".sound").click(function(){ // 音量on/off
        if($(this).attr('type') == "off"){
            $(this).hide();
            $(".sound[type=on]").show();
            $("#audio0").get(0).volume = 1;
        }else{
            $(this).hide();
            $(".sound[type=off]").show();
            $("#audio0").get(0).volume = 0;
        }
    })


    $("#font_size_up").click(function(){ // 字體放大
        var size = $("body, html").css('font-size').split("px")[0];
        var ol = $("ol").css('font-size').split("px")[0];
        if(size < 26){
            size = parseInt(size)+2;
            ol = parseInt(ol)+2;
            $("body, html").css('font-size', size+"px");
            $("span").css('font-size', size+"px");
            $("ol").css('font-size', ol+"px");
            $("button").css('font-size', size+"px");
        }
    })
    $("#font_size_down").click(function(){ // 字體縮小
        var size = $("body, html").css('font-size').split("px")[0];
        var ol = $("ol").css('font-size').split("px")[0];
        if(size > 14){
            size = parseInt(size)-2;
            ol = parseInt(ol)-2;
            $("body, html").css('font-size', size+"px");
            $("span").css('font-size', size+"px");
            $("ol").css('font-size', ol+"px");
            $("button").css('font-size', size+"px");
        }
    })


    $("#data_count1").click(function(){
        $("#data_count1").hide();
        $("#data_count2").show();
        $("#table1").show();
        $("#table_div").hide();
    })
    $("#data_count2").click(function(){
        $("#data_count1").show();
        $("#data_count2").hide();
        $("#table1").hide();
        $("#table_div").show();
    })

    $("#name").keyup(function(){  //輸入名字
        if($("#name").val() != ""){
            $("#continue").removeClass('click_off');
            $("#continue").addClass('button');
            $("#continue").removeAttr('disabled');
        }else{
            $("#continue").removeClass('button');
            $("#continue").addClass('click_off');
            $("#continue").attr('disabled', '');
        }
    })
    $("#continue").click(function(){ //送出資料到php檔案
        $.ajax({
            url: "php/register.php",
            type: "POST",
            data:{
                name: $("#name").val(),
                score: canvas.score,
                time: canvas.monster_time
            },
            success: function(data) {
                $('#table1 tr:not(:first), #table2 tr:not(:first)').remove();
                var data = JSON.parse(data);
                var max = 0;
                var count = 0;
                var tf = true;
                
                $.each(data, function(key, val){
                    if(parseInt(val[0]) >= parseInt(max)){
                        max = val[0];
                    }
                    var table = "<tr type='"+val[0]+"'><td>"+parseInt(key+1)+"</td><td>"+val[1]+"</td><td>"+val[2]+"</td><td>"+val[3]+"</td></tr>";
                    $("#table2").append(table);
                });
                $("#table2 tr[type="+max+"]").addClass("tr_color");
               
                for(i=0 ; i<=2 ; i++){
                    if(data[i] === undefined) break;
                    var table = "<tr><td>"+parseInt(i+1)+"</td><td>"+data[i][1]+"</td><td>"+data[i][2]+"</td><td>"+data[i][3]+"</td></tr>";
                    if(data[i][0] == max){
                        tf = false;
                        table = "<tr class='tr_color'><td>"+parseInt(i+1)+"</td><td>"+data[i][1]+"</td><td>"+data[i][2]+"</td><td>"+data[i][3]+"</td></tr>";
                    }
                    $("#table1").append(table);
                    count+=1;
                }
                if(count == 3 && tf){
                    $.each(data, function(key, val){
                        if(val[0] == max){
                            var table = "<tr class='tr_color'><td>"+parseInt(key+1)+"</td><td>"+val[1]+"</td><td>"+val[2]+"</td><td>"+val[3]+"</td></tr>";
                            $("#table1").append(table);
                        }
                    });
                }

                $("#game_end").fadeOut();
                $("#game_count").fadeIn();
            }
        })
    })

    $("body, html").keydown(function(e){ // 按下鍵盤
        if(e.keyCode == 80 && canvas.keydown_tf){
            /*canvas.keydown_tf = false;
            if(canvas.keydown_tf){
                $(".restart[type=stop]").click();
            }else{
                $(".restart[type=start]").click();
            }*/
        }else if(canvas.game_st){
            if(e.keyCode == 32 && canvas.keydown_tf){
                canvas.shpe0_attack_produce();
            }
        }
    })
    $("body, html").keyup(function(e){ // 放開鍵盤
        if(!canvas.keydown_tf){
            canvas.keydown_tf = true;
        }
    })

    $("#rocker").on('mousemove', function (e) {
        var w = $(this).width(),
            h = $(this).height(),
            cx = $(this).offset().left,
            cy = $(this).offset().top,
            ox = cx + w / 2,
            oy = cy + h / 2,
            x = (e.pageX - ox)/10,
            y = (e.pageY - oy)/10;
        
        if(x > 0){
            x += (canvas.grade/2)
        }else{
            x -= (canvas.grade/2)
        }if(y > 0){
            y += (canvas.grade/2)
        }else{
            y -= (canvas.grade/2)
        }
        
        canvas.x = x;
        canvas.y = y;
    }).on('mouseleave', function(e){
        canvas.x = 0;
        canvas.y = 0;
    });
    $("#fire").click(function(){
        if(canvas.game_st){
            canvas.shpe0_attack_produce();
        }
    })

    $('.click_water_wave').on('click', function (e) { //按鈕的水波特效
        $('.ripple').remove();

        var x = $(this).offset().left,
            y = $(this).offset().top,
            w = $(this).width(),
            h = $(this).width();

        $(this).prepend('<span class="ripple rippleDrop"></span>');
        $('.ripple').css({
            top: e.pageY - y - h/2,
            left: e.pageX - x - w/2,
            width: w,
            height: h,
        })
    })
})


function Canvas(){ //畫布
    this.rocket_x = 0;
    this.rocket_y = 0;
 
    this.grade = 0; //遊戲等級
    this.score = 0; //分數
    
    this.time = ""; //計時的function
    this.m = 0; //分鐘
    this.s = 0; //秒數

    this.keydown_tf = true; //禁止長按攻擊鍵
    this.game_st = false; //遊戲開始or暫停

    this.effect_time = 0;
    this.monster_time = 0;
    this.random_time1 = 1;
    this.random_time2 = 0;

    var rocket = $("#rocket")[0];
    var obj = new Shape(rocket, 'shape0', 15, 0, 300, 110, 50);

    this.shape0 = obj //主角飛船
    this.shape1 = []; //敵人飛船
    this.shape2 = []; //友方飛船
    this.shape3 = []; //隕石
    this.attack1 = []; //主角攻擊
    this.attack2 = []; //敵人攻擊
    this.supply = []; //補給包
    this.delete = []; //刪除物件

    this.bg_position = 0;  // 星空背景移動
    this.bg_content_x = ['', 90, 700, 250, 750, 200, 500]; //星球背景x軸
    this.bg_content_y = ['', 70, 150, 240, 300, 400, 420]; //星球背景ㄗ軸
}
function Shape(obj, name, power, left, top, width, height){ //角色
    this.obj = obj || "";
    this.name = name || "";
    this.power = power || 0;
    this.delete = 0;

    this.number = 1; //特效
    this.tf = true; //特效
    
    this.left = left || 0;
    this.top = top || 0;

    this.width = width || 0;
    this.height = height || 0;
}


Shape.prototype.draw = function(){ //畫Shape
    switch (this.name){
        case "shape0":
            if(this.left + canvas.x > 0 && this.left + this.width + canvas.x < 960){
                this.left += canvas.x;
            }if(this.top + canvas.y > 0 && this.top + this.height + canvas.y < 600){
                this.top += canvas.y;
            }
            break;
        case "shape1":
            this.left = this.left-3-canvas.grade;
            break;
        case "shape2":
            this.left = this.left-1-canvas.grade;
            break;
        case "shape3": 
            this.left = this.left-2-canvas.grade;
            break;
        case "shape4":
            this.left = this.left-1.5-canvas.grade;
            break;
        case "attack1":
            this.left = this.left+4+canvas.grade;
            break;
        case "attack2":
            this.left = this.left-4-canvas.grade;
            break;
        case "supply":
            this.top = this.top+1+canvas.grade;
            break;
    }
    $(this.obj).css('left', this.left+'px');
    $(this.obj).css('top', this.top+'px');
}
Shape.prototype.shape_add = function(name){ //新增角色
    $("#canvas").append('<img class="'+name+' img shape" src="img/'+name+'1.png">');
    this.obj = $("."+name+":last");
    this.name = name;
    this.left = 970;
    this.top = random(10,510);
    this.width = 80;
    this.height = 80;

    if(name == "shape1"){
        this.power = 15;
        canvas.shape1.push(this);
    }else if(name == "shape2"){
        this.power = 15;
        canvas.shape2.push(this);
    }else if(name == "shape3"){
        this.power = 30;
        canvas.shape3.push(this);
    }else if(name == "shape4"){
        this.power = 30;
        canvas.shape3.push(this);
    }
    canvas.power_change();
}
Shape.prototype.attack_add = function(obj, name){ //新增攻擊
    $("#canvas").append('<img class="'+name+' img shape" src="img/'+name+'.png">');
    this.obj = $("."+name+":last");
    this.name = name;
    this.top = obj.top + (obj.height/2);
    this.width = 50;
    this.height = 3.5;

    if(name == 'attack1'){
        this.left = obj.left + obj.width;
        canvas.attack1.push(this);
        canvas.audio_add('audio1');
    }else{
        this.left = obj.left - this.width;
        canvas.attack2.push(this);
    }
}
Shape.prototype.supply_add = function(name){ //新增補給包
    var shap = '<img class="supply img shape" src="img/oil.png">';
    $("#canvas").append(shap);

    this.obj = $(".supply:last");
    this.name = name;
    this.left = random(50,870);
    this.top = -30;
    // this.width = 40;
    // this.height = 15;
    this.width = 65;
    this.height = 90;

    canvas.supply.push(this);
}


Shape.prototype.effect = function(){ //角色特效
    if(this.name == "shape1" || this.name == "shape2"){
        this.number += 1;
        if(this.number > 4){
            this.number = 1;
        }
        $(this.obj).attr('src', 'img/'+this.name+this.number+'.png');
    }else if(this.name == "shape3" || this.name == "shape4"){
        this.number += 2;
        if(this.number > 360){
            this.number = 0;
        }
        $(this.obj).css('transform', 'rotate('+this.number+'deg)');
    }else if(this.name == "supply"){
        if(this.tf){
            this.number += 2;
            $(this.obj).css('transform', 'rotate('+(this.number-40)+'deg)');
            if(this.number > 80){
                this.tf = false;
            }
        }else{
            this.number -= 2;
            $(this.obj).css('transform', 'rotate('+(this.number-40)+'deg)');
            if(this.number < 0){
                this.tf = true;
            }
        }
    }
}


Canvas.prototype.effect_change = function(){ //增加特效
    this.effect_time += 1;
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.effect_time%10 == 0){
            this.shape1[i].effect();
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.effect_time%10 == 0){
            this.shape2[i].effect();
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        this.shape3[i].effect();
    }
    for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.effect_time%3 == 0){
            this.supply[i].effect();
        }
    }
}

Canvas.prototype.shpe0_attack_produce = function(){ //產生我方攻擊
    shape = new Shape();
    shape.attack_add(this.shape0, 'attack1');
    this.keydown_tf = false;
}
Canvas.prototype.monster_produce = function(){ //產生敵軍
    if(this.grade == 0){
        this.random_time1 += random(3,4);//3-6
    }else{
        this.random_time1 += random(2,2);//2-4
    }
    var count = random(2,3); //2-4
    for(i=1 ; i<=count ; i++){
        var shape = new Shape(), name="";
        
        for(j=1;j<=100;j++){
            name = "shape"+random(1,4);//1-4
            if(this.after_monster != name){
                this.after_monster = name;
                break;
            }
        }

        shape.shape_add(name);
    }
}
Canvas.prototype.attack_produce = function(){ //產生攻擊
    for(i=0 ; i<canvas.shape1.length ; i++){
        shape = new Shape();
        shape.attack_add(canvas.shape1[i], 'attack2');
    }
}
Canvas.prototype.supply_produce = function(){ //產生補給包
    this.random_time2 += random(5,3);//5-7
    shape = new Shape();
    shape.supply_add("supply");
}
Canvas.prototype.audio_add = function(name){ //產生音效
    if(name == 'audio1'){
        $("#game_audio").append('<audio class="audio1" src="music/shoot.mp3" autoplay>射擊 - 瀏覽器無法支援</audio>');
    }else{
        $("#game_audio").append('<audio class="audio2" src="music/destroyed.mp3" autoplay>爆炸 - 瀏覽器無法支援</audio>');
    }
    window.setTimeout(function () {
        $("."+name+":first").remove();
    }, 500);
}


Canvas.prototype.touch = function(){ //碰撞
    var shape0 = this.shape0;
    
    //主角撞敵人
    for(i = this.shape1.length-1 ; i >= 0 ; i--){
        if(
            Math.abs((shape0.left+(shape0.width/2))-(this.shape1[i].left+(this.shape1[i].width/2))) < ((shape0.width/2)+(this.shape1[i].width/2)) &&
            Math.abs((shape0.top+(shape0.height/2))-(this.shape1[i].top+(this.shape1[i].height/2))) < ((shape0.height/2)+(this.shape1[i].height/2))
        ){
            this.touch1(1, shape0, i);
            canvas.power_change();
            return false;
        }
    }

    //主角撞友人
    for(i = this.shape2.length-1 ; i >= 0 ; i--){
        if(
            Math.abs((shape0.left+(shape0.width/2))-(this.shape2[i].left+(this.shape2[i].width/2))) < ((shape0.width/2)+(this.shape2[i].width/2)) &&
            Math.abs((shape0.top+(shape0.height/2))-(this.shape2[i].top+(this.shape2[i].height/2))) < ((shape0.height/2)+(this.shape2[i].height/2))
        ){
            this.touch1(2, shape0, i);
            canvas.power_change();
            return false;
        }
    }

    //主角撞隕石
    for(i = this.shape3.length-1 ; i >= 0 ; i--){
        if(
            Math.abs((shape0.left+(shape0.width/2))-(this.shape3[i].left+(this.shape3[i].width/2))) < ((shape0.width/2)+(this.shape3[i].width/2)) &&
            Math.abs((shape0.top+(shape0.height/2))-(this.shape3[i].top+(this.shape3[i].height/2))) < ((shape0.height/2)+(this.shape3[i].height/2))
        ){
            this.touch1(3, shape0, i);
            canvas.power_change();
            return false;
        }
    }

    //主角被攻擊
    for(i = this.attack2.length-1 ; i >= 0 ; i--){
        if(
            Math.abs((shape0.left+(shape0.width/2))-(this.attack2[i].left+(this.attack2[i].width/2))) < ((shape0.width/2)+(this.attack2[i].width/2)) &&
            Math.abs((shape0.top+(shape0.height/2))-(this.attack2[i].top+(this.attack2[i].height/2))) < ((shape0.height/2)+(this.attack2[i].height/2))
        ){
            this.touch2(shape0, i);
            return false
        }
    }
    
    for(k = this.attack1.length-1; k >= 0 ; k--){
        var attack1 = this.attack1[k];

        //攻擊打中飛船
        for(i = this.shape1.length-1 ; i >= 0 ; i--){
            if(
                Math.abs((attack1.left+(attack1.width/2))-(this.shape1[i].left+(this.shape1[i].width/2))) < ((attack1.width/2)+(this.shape1[i].width/2)) &&
                Math.abs((attack1.top+(attack1.height/2))-(this.shape1[i].top+(this.shape1[i].height/2))) < ((attack1.height/2)+(this.shape1[i].height/2))
            ){
                tf = false;                    
                this.touch3(1, k, this.shape1[i]);
                canvas.power_change();
                return false
            }
        }

        //攻擊打中友方
        for(i = this.shape2.length-1 ; i >= 0 ; i--){
            if(
                Math.abs((attack1.left+(attack1.width/2))-(this.shape2[i].left+(this.shape2[i].width/2))) < ((attack1.width/2)+(this.shape2[i].width/2)) &&
                Math.abs((attack1.top+(attack1.height/2))-(this.shape2[i].top+(this.shape2[i].height/2))) < ((attack1.height/2)+(this.shape2[i].height/2))
            ){
                tf = false;  
                this.touch3(2, k, this.shape2[i]);
                canvas.power_change();
                return false
            }
        }

        //攻擊打中隕石
        for(i = this.shape3.length-1 ; i >= 0 ; i--){
            if(
                Math.abs((attack1.left+(attack1.width/2))-(this.shape3[i].left+(this.shape3[i].width/2))) < ((attack1.width/2)+(this.shape3[i].width/2)) &&
                Math.abs((attack1.top+(attack1.height/2))-(this.shape3[i].top+(this.shape3[i].height/2))) < ((attack1.height/2)+(this.shape3[i].height/2))
            ){
                tf = false;  
                this.touch3(3, k, this.shape3[i]);
                canvas.power_change();
                return false
            }
        }
    }

    //主角吃補給包
    for(i = this.supply.length-1 ; i >= 0 ; i--){
        if(
            Math.abs((shape0.left+(shape0.width/2))-(this.supply[i].left+(this.supply[i].width/2))) < ((shape0.width/2)+(this.supply[i].width/2)) &&
            Math.abs((shape0.top+(shape0.height/2))-(this.supply[i].top+(this.supply[i].height/2))) < ((shape0.height/2)+(this.supply[i].height/2))
        ){
            this.touch4(shape0, i);
            canvas.power_change();
            return false
        }
    }
}
Canvas.prototype.touch1 = function(type, a, b){
    a.power -= 15;
    this.audio_add(type);

    if(type == 1){ //主角撞敵人
        $(this.shape1[b].obj).remove();
        this.shape1.splice(b, 1);
    }else if(type == 2){ //主角撞友人
        $(this.shape2[b].obj).remove();
        this.shape2.splice(b, 1);
    }else if(type == 3){ //主角撞隕石
        $(this.shape3[b].obj).remove();
        this.shape3.splice(b, 1);
    }

    this.game_over();
    $("#canvas").append('<div class="explosion" style="position: absolute; top: '+b.top+'px; left: '+b.left+'px; width: '+b.width+'px; height: '+b.height+'px;"></div>');
}
Canvas.prototype.touch2 = function(a, b){
    a.power -= 15;
    $(this.attack2[b].obj).remove();
    this.attack2.splice(b, 1);
    this.game_over();
}
Canvas.prototype.touch3 = function(type, a, b){
    $(this.attack1[a].obj).remove();
    this.attack1.splice(a, 1);
    b.power -= 15;

    if(type == 1){//攻擊打中飛船
        $("#canvas").append('<div class="explosion" style="position: absolute; top: '+b.top+'px; left: '+b.left+'px; width: '+b.width+'px; height: '+b.height+'px;"></div>');
        this.score += 5;
    }else if(type == 2){//攻擊打中友方
        $("#canvas").append('<div class="explosion" style="position: absolute; top: '+b.top+'px; left: '+b.left+'px; width: '+b.width+'px; height: '+b.height+'px;"></div>');
        this.score -= 10;
    }else if(type == 3){//攻擊打中隕石
        if(b.power == 0){
            $("#canvas").append('<div class="explosion" style="position: absolute; top: '+b.top+'px; left: '+b.left+'px; width: '+b.width+'px; height: '+b.height+'px;"></div>');
            this.score += 10;
        }else if(b.name == "shape3"){
            $(b.obj).attr('src', "img/shape32.png");
        }else{
            $(b.obj).attr('src', "img/shape42.png");
        }
    }
}
Canvas.prototype.touch4 = function(a, b){
    a.power += 15;
    if(a.power > 30){
        a.power = 30;
    }
    $(this.supply[b].obj).remove();
    this.supply.splice(b, 1);
}


Canvas.prototype.ready_remove = function(){ //預備刪除
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.shape1[i].left + this.shape1[i].width <= 0 || this.shape1[i].power <= 0 && this.shape1[i].delete <= 100){
            this.shape1[i].delete += 1;
        }
    }for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.shape2[i].left + this.shape2[i].width <= 0 || this.shape2[i].power <= 0 && this.shape2[i].delete <= 100){
            this.shape2[i].delete += 1;
        }
    }for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        if(this.shape3[i].left + this.shape3[i].width <= 0 || this.shape3[i].power <= 0 && this.shape3[i].delete <= 100){
            this.shape3[i].delete += 1;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){ //主角攻擊
        if(this.attack1[i].left >= 960 && this.attack1[i].delete <= 100){
            this.attack1[i].delete += 1;
        }
    }for(i = 0 ; i < this.attack2.length ; i++){ //敵人攻擊
        if(this.attack2[i].left + this.attack2[i].width <= 0 && this.attack2[i].delete <= 100){
            this.attack2[i].delete += 1;
        }
    }for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.supply[i].top >= 600 && this.supply[i].delete <= 100){
            this.supply[i].delete += 1;
        }
    }
}
Canvas.prototype.remove = function(){ //刪除物件
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.shape1[i].delete >= 5){
            $(this.shape1[i].obj).remove();
            this.shape1.splice(i,1);
            $(".explosion").remove();
            return false;
        }
    }for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.shape2[i].delete >= 5){
            $(this.shape2[i].obj).remove();
            this.shape2.splice(i,1);
            $(".explosion").remove();
            return false;
        }
    }for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        if(this.shape3[i].delete >= 5){
            $(this.shape3[i].obj).remove();
            this.shape3.splice(i,1);
            $(".explosion").remove();
            return false;
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){ //主角攻擊
        if(this.attack1[i].delete >= 5){
            $(this.attack1[i].obj).remove();
            this.attack1.splice(i,1);
            return false;
        }
    }for(i = 0 ; i < this.attack2.length ; i++){ //敵人攻擊
        if(this.attack2[i].delete >= 5){
            $(this.attack2[i].obj).remove();
            this.attack2.splice(i,1);
            return false;
        }
    }for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.supply[i].delete >= 5){
            $(this.supply[i].obj).remove();
            this.supply.splice(i,1);return false;
        }
    }
}
Canvas.prototype.draw = function(){ //畫Canvas
    for(i = 0 ; i < this.shape1.length ; i++){ //敵人
        if(this.shape1[i].delete == 0){
            this.shape1[i].draw();
        }
    }
    for(i = 0 ; i < this.shape2.length ; i++){ //友人
        if(this.shape2[i].delete == 0){
            this.shape2[i].draw();
        }
    }
    for(i = 0 ; i < this.shape3.length ; i++){ //隕石
        if(this.shape3[i].delete == 0){
            this.shape3[i].draw();
        }
    }
    for(i = 0 ; i < this.attack1.length ; i++){ //主角攻擊
        if(this.attack1[i].delete == 0){
            this.attack1[i].draw();
        }
    }
    for(i = 0 ; i < this.attack2.length ; i++){ //敵人攻擊
        if(this.attack2[i].delete == 0){
            this.attack2[i].draw();
        }
    }
    for(i = 0 ; i < this.supply.length ; i++){ //補給包
        if(this.supply[i].delete == 0){
            this.supply[i].draw();
        }
    }
    this.shape0.draw(); //主角
}


Canvas.prototype.game_start = function(){  //遊戲開始
    this.game_st = true;
    this.time_change();
    game_time = setInterval(function(){
        canvas.effect_change();
        canvas.bg_change();
        canvas.touch();

        canvas.ready_remove();
        canvas.remove();
        canvas.draw();

        canvas.game_end();
    },20);
}
Canvas.prototype.game_stop = function (){  //遊戲暫停
    canvas.game_st = false;
    clearTimeout(canvas.time);
    clearInterval(game_time);
}
Canvas.prototype.game_end = function(){ //遊戲結束
    if(this.shape0.power <= 0){
        this.shape0.power = 0;
        this.power_change();

        $(".restart[type=stop]").click();
        $("#game_windows").show();
        $("#game_end").show();
    }
}


Canvas.prototype.time_change = function(){ //計時時間
    if(this.monster_time == this.random_time1) this.monster_produce();
    if(this.monster_time == this.random_time2) this.supply_produce();
    if(this.grade < 5 && this.monster_time%5 == 0) this.grade += 1;

    if(this.grade <= 6){
        if(this.monster_time%2 == 0){
            this.attack_produce();
        }
    }else{
        this.attack_produce();
    }
    
    this.shape0.power -= 1;
    this.power_change();

    this.monster_time +=1;
    
    this.s += 1;

    if(this.s == 60){
        this.s = 0;
        this.m += 1;
    }
    ss = this.s; mm = this.m;
    if(this.s < 10){
        ss = "0" + this.s;
    }if(this.m < 10){
        mm = "0" + this.m;
    }
    
    $("#timing").text(mm + ":" + ss);
    this.time = setTimeout("canvas.time_change()",1000);
}
Canvas.prototype.bg_change = function (){ //背景移動
    for(i = 1 ; i <= 6 ; i++){
        if(this.bg_content_x[i] + $("#scenes"+i).width() <= 0){
            this.bg_content_x[i] = 960;
        }
        if(i == 1){
            this.bg_content_x[i] -= 1;
        }else if(i == 2){
            this.bg_content_x[i] -= 4;
        }else if(i == 3){
            this.bg_content_x[i] -= 2;
        }else if(i == 4){
            this.bg_content_x[i] -= 3;
        }else if(i == 5){
            this.bg_content_x[i] -= 5;
        }else if(i == 6){
            this.bg_content_x[i] -= 6;
        }
        $("#scenes"+i).css('transform', 'translate(' + this.bg_content_x[i] + 'px, ' + this.bg_content_y[i]  + 'px)');
    }
    this.bg_position -= 2;
    $("#game").css('background-position-x', this.bg_position+"px");
}
Canvas.prototype.power_change = function(){ //顯示油量
    $("#score").text(this.score);
    $("#power").text(this.shape0.power);
    $("#power-counter").css('height', parseInt(this.shape0.power/30*100)+"%");
}
Canvas.prototype.game_over = function(){
    $('#game').addClass('gameover');
    window.setTimeout(function () {
        $('#game').removeClass('gameover');
    }, 650);
}


function random(min, max){ //產生亂數
    return parseInt(Math.random()*max)+min;
}