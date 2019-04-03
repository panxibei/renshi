@extends('renshi.layouts.mainbase_cube')

@section('my_title')
Renshi(Jiaban) - 
@parent
@endsection

@section('my_style')
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_body')
@parent

something here.
<br>
LOGO HERE
<br><br>

<cube-form :model="model" @validate="validateHandler" @submit="submitHandler">
  <cube-form-group>
    <cube-form-item :field="fields[0]">
        <cube-input v-model.lazy="jiaban_add_uid" placeholder="输入工号"></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[1]">
        <cube-input v-model.lazy="jiaban_add_applicant" placeholder="姓名"></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[2]">
        <cube-input v-model.lazy="jiaban_add_department" placeholder="部门"></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[3]">
        <cube-input v-model.lazy="jiaban_add_startdate" @focus="showDateTimePicker_startdate" placeholder="选择开始时间"></cube-input>
        <!-- <cube-button @click="showDateTimePicker">@{{model.dateValue || 'Please select date'}}</cube-button> -->
        <!-- <date-picker ref="datePicker" :min="[2008, 8, 8]" :max="[2020, 10, 20]" @select="dateSelectHandler"></date-picker> -->
    </cube-form-item>
    <cube-form-item :field="fields[4]">
        <cube-input v-model.lazy="jiaban_add_enddate" @focus="showDateTimePicker_enddate" placeholder="选择结束时间"></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[5]">
        <cube-select v-model.lazy="jiaban_add_duration" :options="jiaban_add_duration_options" title="选择时长" placeholder="选择时长"></cube-select>
    </cube-form-item>
    <cube-form-item :field="fields[6]">
        <cube-select v-model.lazy="jiaban_add_category" :options="jiaban_add_category_options" title="选择类别" placeholder="选择类别"></cube-select>
    </cube-form-item>
    <cube-form-item :field="fields[7]">
        <cube-textarea v-model.lazy="jiaban_add_reason" maxlength="100" placeholder="在此填写理由..."></cube-textarea>
    </cube-form-item>
    <cube-form-item :field="fields[8]">
        <cube-textarea v-model.lazy="jiaban_add_remark" maxlength="100" placeholder="在些填写备注..."></cube-textarea>
    </cube-form-item>
  </cube-form-group>
  <cube-form-group>
  <br>
    <cube-button type="submit">Submit</cube-button>
    <br>
    <cube-button type="reset">Reset</cube-button>
  </cube-form-group>
</cube-form>



<br><br>




something others here.


@endsection

@section('my_footer')
@parent

@endsection

@section('my_js_others')
@parent
<script>
var vm_app = new Vue({
	el: '#app',
	data: {

        jiaban_add_uid: '',
        jiaban_add_applicant: '',
        jiaban_add_department: '',
        jiaban_add_startdate: '',
        jiaban_add_enddate: '',
        jiaban_add_duration: '',
        jiaban_add_duration_options: [2018, 2019, 2020],
        jiaban_add_category: '',
        jiaban_add_category_options: ['平时加班', '双休加班', '节假日加班'],
        jiaban_add_reason: '',
        jiaban_add_remark: '',




        model: {
            inputValue: '',
            pcaValue: [],
            dateValue: ''
        },
        fields: [
            { //0
                // type: 'select',
                // modelKey: 'jiaban_add_uid',
                label: '工号',
                rules: {
                    required: true
                }
            },
            { //1
                // type: 'input',
                // modelKey: 'jiaban_add_applicant',
                label: '申请人姓名',
                rules: {
                    required: false
                },
                // validating when blur
                trigger: 'blur'
            },
            { //2
                // type: 'input',
                // modelKey: 'jiaban_add_department',
                label: '申请人部门',
                rules: {
                    required: false
                },
                trigger: 'blur'
            },
            { //3
                // modelKey: 'jiaban_add_startdate',
                label: '开始时间',
                rules: {
                    required: true
                }
            },
            { //4
                // modelKey: 'dateValue',
                label: '结束时间',
                rules: {
                    required: true
                }
            },
            { //5
                // type: 'input',
                // modelKey: 'inputValue',
                label: '时长',
                rules: {
                    required: true
                }
            },
            { //6
                // type: 'input',
                // modelKey: 'inputValue',
                label: '类别',
                rules: {
                    required: true
                }
            },
            { //7
                // type: 'textarea',
                // modelKey: 'jiaban_add_reason',
                label: '理由',
                rules: {
                    required: true
                },
                // props: {
                //     placeholder: "加班理由",
                //     maxlength: 100,
                //     // autofocus: true
                // },
                // debounce validate
                // if set to true, the default debounce time will be 200(ms)
                // debounce: 100
            },
            { //8
                // type: 'textarea',
                // modelKey: 'jiaban_add_remark',
                label: '备注',
                rules: {
                    required: false
                },
                // props: {
                //     placeholder: "备注",
                //     maxlength: 100,
                //     // autofocus: true
                // },
                // debounce: 100
            },



        ],










        







        









        column1: [{ text: '剧毒', value: '剧毒'}, { text: '蚂蚁', value: '蚂蚁' },
            { text: '幽鬼', value: '幽鬼' }],

        column2: [{ text: '剧毒2', value: '剧毒2'}, { text: '蚂蚁2', value: '蚂蚁2' },
            { text: '幽鬼2', value: '幽鬼2' }],

        checkList: ['1', '4'],
        options0: [
            '1',
            '2',
            {
            label: '3',
            value: '3',
            disabled: true
            },
            {
            label: '4',
            value: '4',
            disabled: true
            }
        ],


        // validity: {},
        // valid: undefined,
        model: {
            checkboxValue: false,
            checkboxGroupValue: [],
            inputValue: '',
            radioValue: '',
            rateValue: 0,
            selectValue: 2018,
            switchValue: true,
            textareaValue: '',
            uploadValue: [],
        },
        schema: {
            groups: [
            {
                legend: '基础',
                fields: [
                    {
                        type: 'textarea',
                        modelKey: 'textareaValue',
                        label: '加班理由',
                        rules: {
                        required: true
                        },
                        // debounce validate
                        // if set to true, the default debounce time will be 200(ms)
                        debounce: 100
                    },
                    {
                        type: 'textarea',
                        modelKey: 'textareaValue',
                        label: '备注',
                        rules: {
                        required: true
                        },
                        // debounce validate
                        // if set to true, the default debounce time will be 200(ms)
                        debounce: 100
                    },
                    
                {
                    type: 'checkbox',
                    modelKey: 'checkboxValue',
                    props: {
                        option: {
                            label: 'Checkbox',
                            value: true
                        }
                    },
                    rules: {
                    required: true
                    },
                    messages: {
                    required: 'Please check this field'
                    }
                },
                {
                    type: 'checkbox-group',
                    modelKey: 'checkboxGroupValue',
                    label: 'CheckboxGroup',
                    props: {
                    options: ['1', '2', '3']
                    },
                    rules: {
                    required: true
                    }
                },
                {
                    type: 'input',
                    modelKey: 'inputValue',
                    label: 'Input',
                    props: {
                    placeholder: '请输入'
                    },
                    rules: {
                    required: true
                    },
                    // validating when blur
                    trigger: 'blur'
                },
                {
                    type: 'radio-group',
                    modelKey: 'radioValue',
                    label: 'Radio',
                    props: {
                    options: ['1', '2', '3']
                    },
                    rules: {
                    required: true
                    }
                },
                {
                    type: 'select',
                    modelKey: 'selectValue',
                    label: 'Select',
                    props: {
                    options: [2015, 2016, 2017, 2018, 2019, 2020]
                    },
                    rules: {
                    required: true
                    }
                },
                {
                    type: 'switch',
                    modelKey: 'switchValue',
                    label: 'Switch',
                    rules: {
                    required: true
                    }
                },
                {
                    type: 'textarea',
                    modelKey: 'textareaValue',
                    label: 'Textarea',
                    rules: {
                    required: true
                    },
                    // debounce validate
                    // if set to true, the default debounce time will be 200(ms)
                    debounce: 100
                }
                ]
            },
            {
                legend: '高级',
                fields: [
                {
                    type: 'rate',
                    modelKey: 'rateValue',
                    label: 'Rate',
                    rules: {
                    required: true
                    }
                },
                {
                    type: 'upload',
                    modelKey: 'uploadValue',
                    label: 'Upload',
                    events: {
                    'file-removed': (...args) => {
                        console.log('file removed', args)
                    }
                    },
                    rules: {
                    required: true,
                    uploaded: (val, config) => {
                        return Promise.all(val.map((file, i) => {
                        return new Promise((resolve, reject) => {
                            if (file.uploadedUrl) {
                            return resolve()
                            }
                            // fake request
                            setTimeout(() => {
                            if (i % 2) {
                                reject(new Error())
                            } else {
                                file.uploadedUrl = 'uploaded/url'
                                resolve()
                            }
                            }, 1000)
                        })
                        })).then(() => {
                        return true
                        })
                    }
                    },
                    messages: {
                    uploaded: '上传失败'
                    }
                }
                ]
            },
            {
                fields: [
                {
                    type: 'submit',
                    label: 'Submit'
                },
                {
                    type: 'reset',
                    label: 'Reset'
                }
                ]
            }
            ]
        },
        options: {
            scrollToInvalidField: true,
            layout: 'standard' // classic fresh
        },

        inputvalue: '',
        clearable: {
            visible: true,
            blurHidden: true
        }


	},
	methods: {

        // showDateTimePicker
        showDateTimePicker_startdate() {
            if (!this.dateTimePicker_startdate) {
                this.dateTimePicker_startdate = this.$createDatePicker({
                title: '选择开始时间',
                min: new Date(2018, 12, 1, 0, 0, 0),
                // max: new Date(2020, 9, 20, 20, 59, 59),
                max: new Date(this.jiaban_add_enddate || '2099-12-31 23:59:59'),
                value: new Date(),
                columnCount: 6,
                onSelect: this.selectHandle_startdate,
                onCancel: this.cancelHandle_startdate
                })
            }

            this.dateTimePicker_startdate.show()
        },
        selectHandle_startdate(date, selectedVal, selectedText) {
            this.jiaban_add_startdate = date.Format("yyyy-MM-dd hh:mm:ss")
            // this.$createDialog({
            //     type: 'warn',
            //     content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            //     icon: 'cubeic-alert'
            // }).show()
        },
        cancelHandle_startdate() {
            // this.$createToast({
            //     type: 'correct',
            //     txt: 'Picker canceled',
            //     time: 1000
            // }).show()
        },

        // showDateTimePicker
        showDateTimePicker_enddate() {
            if (!this.dateTimePicker_enddate) {
                this.dateTimePicker_enddate = this.$createDatePicker({
                title: '选择结束时间',
                // min: new Date(2008, 7, 8, 8, 0, 0),
                min: new Date(this.jiaban_add_startdate || '2019-01-01 00:00:00'),
                max: new Date(2099, 12, 31, 23, 59, 59),
                value: new Date(),
                columnCount: 6,
                onSelect: this.selectHandle_enddate,
                onCancel: this.cancelHandle_enddate
                })
            }

            this.dateTimePicker_enddate.show()
        },
        selectHandle_enddate(date, selectedVal, selectedText) {
            this.jiaban_add_enddate = date.Format("yyyy-MM-dd hh:mm:ss")
            // this.$createDialog({
            //     type: 'warn',
            //     content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            //     icon: 'cubeic-alert'
            // }).show()
        },
        cancelHandle_enddate() {
            // this.$createToast({
            //     type: 'correct',
            //     txt: 'Picker canceled',
            //     time: 1000
            // }).show()
        },































        handleClick() {
			console.log('aaaaaaa');
			this.$dialog.alert({
                title: '标题',
                message: '弹窗内容'
                }).then(() => {
                // on close
            });

        },


        // showToast
		showToastTime() {
            const toast = this.$createToast({
                time: 2000,
                txt: 'Toast time 2s'
            })
            toast.show()
        },

        showToastTxtOnly() {
            this.toast = this.$createToast({
                txt: 'Plain txt here!!!!!!!!!!!!!!!',
                type: 'txt'
            })
            this.toast.show()
        },




        

        // form
        submitHandler(e) {
            alert('submit');
            return false;

            e.preventDefault()
            console.log('submit', e)
        },
        validateHandler(result) {
            alert('validate');
            return false;

            this.validity = result.validity
            this.valid = result.valid
            console.log('validity', result.validity, result.valid, result.dirty, result.firstInvalidFieldIndex)
        },
        resetHandler(e) {
            alert('reset');
            return false;
            
            console.log('reset', e)
        },

		



	},
	mounted: function () {

	}
})
</script>
@endsection