<template>
    <div>
        <div v-for="{ nickName, points, id, definition, adminWord } in urldata" v-if="id == 1">
            <div class="adminBlock">
                <div v-if="definition == ''">
                    <div>Админ ещё не загадал слово</div>
                </div>
                <div v-else>
                    <div>Загаданное слово админа: {{ adminWord }}</div>
                </div>
            </div>
        </div>
        <div class="playerBlock">
            <div v-for="{ nickName, points, id, definition } in urldata" v-if="id != 1" class="dataPlayer">
                <div>
                    <div>Имя игрока: {{ nickName }}</div>
                    <div>Очков у игрока: {{ points }}</div>
                    <div v-if="definition == ''">
                        <strong>Игрок ещё не написал определение слова</strong>
                    </div>
                    <div v-else>
                        <strong>Игрок написал определение слова</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'urldata'
        ],
        mounted() {
            this.update()
        },
        methods: {
            update: function () {
                console.log(this.jsonArray);
            }
        },
    }
</script>

<style>
    *{
        font-family: 'Staatliches', cursive;
    }
    body{
        background: #edeef0;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }

    .adminBlock{
        width: 100%;
        padding: 20px;
        font-size: 25px;
        text-align: center;
        border: 2px solid pink;
        color: pink;
        font-weight: bold;
        word-spacing: 10px;
    }

    .playerBlock{
        display:flex;
        width: 100%;
        font-size: 20px;
        color: seagreen;
        padding: 10px;
        border: 1px solid seagreen;
        text-align: center;
    }

    .dataPlayer{
        width: 32%;
        flex-wrap: wrap;
        padding: 10px;
    }

</style>