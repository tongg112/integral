<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">主页</div>

                    <div class="panel-body">
                        <div class="">
                            欢迎回来！你的积分
                            <h1>{{ available }}分</h1>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60"
                                 aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                <span class="sr-only">40% 完成</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <input name="code" type="text" class="form-control" placeholder="花费" v-model="cost">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="cost_integral(cost)">
                                确认消耗{{ cost }}</button>
                            </span>
                        </div>
                        <br />
                        <div class="btn-group btn-group-lg">
                            <button type="button" class="btn btn-default" @click="add_integral(1)">增加 1</button>
                            <button type="button" class="btn btn-default" @click="add_integral(2)">增加 2</button>
                            <button type="button" class="btn btn-default" @click="add_integral(5)">增加 5</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                available: '加载中……',
                cost: 0
            };
        },
        methods: {
            my_integral() {
                axios.post('/api/home', {}).then(function (response) {
                        this.available = response.data.available;
                    }.bind(this)
                ).catch(function (err) {
                    console.log(err)
                });
            },
            add_integral(point) {
                axios.post('/api/change', {point: point}).then(
                    response => {
                        this.available = response.data.available;
                    }
                ).catch(function (err) {
                    console.log(err)
                });
            },
            cost_integral(point) {

                if (!point) {
                    return true;
                }
                axios.post('/api/change', {point: -point}).then(
                    response => {
                        this.available = response.data.available;
                    }
                ).catch(function (err) {
                    console.log(err)
                });
            }
        },
        created() {
            this.my_integral();
        }
    }


</script>
