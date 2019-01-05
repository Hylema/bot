<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css">
    <title>Тест</title>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>
<body>

<div class="app">
    <strong v-bind:class="gleb"> {{ gleb }} </strong><br>
    <span v-on:click="changeText"> Изменить текст </span><br>
    <input type="text" v-on:input="gleb = $event.target.value"><br>
    <input type="text" v-on:input="inc($event.target.value)"><br>
    <span> {{ value }} </span><br>
    <span> {{ doubleValue }} </span><br>
    <button v-on:click="showOr()"> {{ text }} </button><br>
    <span v-if="show"> Текст 1 </span>
    <span v-else> {{ message | localFilterLowerCase | globalFiletFirstLatterInUpperCase }} </span>
    <person-global-component></person-global-component>
    <person-local-component></person-local-component>
</div>

</body>
</html>

<script>

    Vue.filter ('globalFiletFirstLatterInUpperCase', function(value) {
        if(!value){
            return ''
        } else {
            value = value.toString()
            return value.replace(/\b\w/g, function(u) {
                return u.toUpperCase()
            })
        }
    });

    Vue.component('person-global-component', {
        data: function () {
            return {
                persons: [
                    {name: "vasay", age: "24", play: "aion"},
                    {name: "gleb", age: "22", play: "life is strange"},
                    {name: "kirill", age: "21", play: "cs GO"},
                    {name: "victor", age: "22", play: "league of legends"},
                ],
            }
        },
        template: '<div><ul v-for="(person, i) in persons"><li> {{ i + 1 }} - Имя пользователя: {{ person.name }}, имеет возраст: {{ person.age }}, играет в {{ person.play }}</li></ul></div>'
    });

    new Vue({
        el: '.app',
        data: {
            gleb: "Работает!",
            value: 1,
            show: false,
            text: "Показать текст",
            message: "ПРИВЕТ МИР!",
        },
        methods: {
            changeText() {
                this.gleb = "Текст изменен!"
            },
            inc(num) {
                this.value = 1 * num
                if(num == 25){
                    alert("25")
                }
            },
            showOr() {
                if(this.show == false){
                    this.show = true
                    this.text = "Убрать текст"
                } else {
                    this.show = false
                    this.text = "Показать текст"
                }
            }
        },
        computed: {
            doubleValue () {
                return this.value * 2
            }
        },
        filters: {
            localFilterLowerCase (value) {
                return value.toLowerCase ()
            }
        },
        components: {
            'person-local-component': {
                data: function () {
                    return {
                        persons: [
                            {name: "vasay", age: "24", play: "aion"},
                            {name: "gleb", age: "22", play: "life is strange"},
                            {name: "kirill", age: "21", play: "cs GO"},
                            {name: "victor", age: "22", play: "league of legends"},
                        ],
                    }
                },
                template: '<div><ul v-for="(person, i) in persons"><li> {{ i + 1 }} - Имя пользователя: {{ person.name }}, имеет возраст: {{ person.age }}, играет в {{ person.play }}</li></ul></div>'
            }
        }
    });

</script>

<style>

    body{
        height: 100%;
        background: #cccccc;
    }

    .background{
        background: pink;
    }

    .color{
        color:white;
    }



</style>