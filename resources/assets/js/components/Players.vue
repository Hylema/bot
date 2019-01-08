<template>
    <div>


        <div class="playerBlock">
            <div v-for="{ nickName, points, id, definition, playerChoose, adminOrPlayer } in urldata" v-if="id != 1" class="dataPlayer">
                <div>
                    <div>

                        <strong>Имя игрока: {{ nickName }}</strong><br>
                        <strong>Очков у игрока: {{ points }}</strong>

                        <div v-if="definition == ''">
                            <strong>Игрок ещё не написал определение слова</strong>
                        </div>

                        <div v-else>
                            <strong>Игрок написал определение слова</strong>
                        </div>

                    </div>

                    <!--<div v-else>-->
                        <!--<strong>Игроков ещё нету в игре</strong>-->
                    <!--</div>-->

                </div>
            </div>
        </div>


            <div v-for="{ nickName, playerChoose, adminOrPlayer, id } in urldata" v-if="id != 1">
                <div v-if="playerChoose != '' && adminOrPlayer != ''" class="gameResult">
                    <div v-if="adminOrPlayer == 'admin'">
                        <strong>Результаты игры игрока {{ nickName }}</strong><br><br>
                        <strong>Вы выбрали определение админа, вы получаете 3 очка!</strong><br>
                        <strong>Для продолжения игры напишите +</strong><br>
                    </div>
                    <div v-else>
                        <strong>Результаты игры игрока {{ nickName }}</strong><br><br>
                        <strong>Вы выбрали определение игрока с ником {{ playerChoose }}, этот игрок получает 1 очко!</strong><br>
                        <strong>Для продолжения игры напишите +</strong><br>
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