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

                        <div class="form-group">
                            <label>备注(必填)</label>
                            <input type="text" class="form-control" placeholder="描述" v-model="remark">
                        </div>

                        <div class="input-group">
                            <input name="code" type="text" class="form-control" maxlength="3" placeholder="变更积分" v-model="change">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="cost_integral()">
                                确认消耗{{ change }}</button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="add_integral()">
                                确认增加{{ change }}</button>
                            </span>
                        </div>

                    </div>

                    <div class="panel-heading" @click="get_integral_list()">最近明细(点击刷新)</div>

                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>时间</th>
                                <th>积分</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="integral_row in integral_list" >
                                <td>{{ integral_row.created_at}}</td>
                                <!-- 积分变化的文字颜色 -->
                                <td v-bind:class="{ 'text-success': integral_row.changes > 0, 'text-danger': integral_row.changes < 0 }" > {{ integral_row.changes > 0 ? '+' : ''}}{{ integral_row.changes}}</td>
                                <td>{{ integral_row.remark}}</td>
                            </tr>
                            </tbody>
                        </table>
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
                change: '',
                remark: '',
                integral_list:[]
            };
        },
        methods: {
            my_integral() {
                axios.post('/api/home', {}).then(function (response) {
                        this.available = response.data.available;
                        init_data(this);
                    }.bind(this)
                ).catch(function (err) {
                    console.log(err)
                });
            },
            get_integral_list() {
                axios.post('/api/integral_list', {}).then(function (response) {
                        this.integral_list = response.data.integral_list;
                    }.bind(this)
                ).catch(function (err) {
                    console.log(err)
                });
            },
            add_integral() {

                if(!validate_data(this)){
                    return true;
                }

                let req = {
                    point: this.change,
                    remark: this.remark
                };

                axios.post('/api/change', req).then(function (response) {
                        this.available = response.data.available;
                        init_data(this);
                        this.get_integral_list();
                    }.bind(this)
                ).catch(function (err) {
                    console.log(err)
                });
            },
            cost_integral() {

                if(!validate_data(this)){
                    return true;
                }

                let req = {
                    point: -this.change,
                    remark: this.remark
                };
                axios.post('/api/change', req).then(function (response) {
                        this.available = response.data.available;
                        init_data(this);
                        this.get_integral_list();
                    }.bind(this)
                ).catch(function (err) {
                    console.log(err)
                });
            }
        },
        created() {
            this.my_integral();
            this.get_integral_list();
        }
    }

    // 初始化变更积分与备注
    function init_data(data) {
        data.change = '';
        data.remark = '';
    }

    function validate_data(data) {

        if(!data.change){
            alert('请输入需要变更的积分数！');
            return false;
        }

        if(!data.remark){
            alert('请输入备注信息！');
            return false;
        }

        return true;
    }

</script>
