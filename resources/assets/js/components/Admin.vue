<template>
    <div>
        <div v-if="gameJson != ''">
            <div v-for="{ nickName, points, id, definition, adminWord, adminStatus, nameWinner } in gameJson" v-if="id == 1">

                <div v-if="nameWinner == ''">

                    <div v-if="adminStatus != true">
                        <div class="adminBlock">
                            <div v-if="adminWord == ''">
                                <div>Админ ещё не загадал слово</div>
                            </div>
                            <div v-else>
                                <div>Загаданное слово админа: {{ adminWord }}</div>
                            </div>
                        </div>
                    </div>


                    <div v-else class="adminBlock">
                        <strong>Определения: </strong>
                        <div v-for="{ definition } in urldata" v-if="id == 1">
                            <strong>  - {{ definition }}</strong><br>
                        </div>
                        <strong>Выберите самое подходящее определение</strong>
                    </div>


                </div>
                <div v-else class="adminBlock">
                    <strong>Игрок с ником {{ nameWinner }} победил в этой игре</strong><br>
                    <strong>Игра окончена</strong>
                </div>

            </div>
        </div>
        <div v-else class="playerBlock">
            <strong>Админа нету в игре</strong>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return {
              gameJson: [],
          }
        },
        mounted() {
            this.update()
        },
        methods: {
            update: function () {
                axios.get('/api/game').then((response) => {
                    console.log(response);
                   this.gameJson = response.data
                });
            },
        },
    }
</script>


