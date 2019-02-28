<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Mint</title>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset('statics/cube/cube.min.css') }}">

<style type="text/css">


</style>
	
</head>
<body>
<div id="app">

<cube-button @click="showToastTime">Button</cube-button>
<br>
<cube-button @click="showToastTxtOnly">showToastTxtOnly</cube-button>

<br>
<cube-button @click="showPicker1">Picker1</cube-button>
<br>
<cube-button @click="showPicker2">Picker2</cube-button>

<br>
<cube-input v-model="inputvalue" :clearable="clearable"></cube-input>

<br>
<cube-button @click="showDateTimePicker">Date Time Picker</cube-button>

<br>
<cube-checkbox-group v-model="checkList" :options="options0" :horizontal="true"></cube-checkbox-group>

<br>
form
<cube-form
  :model="model"
  :schema="schema"
  :immediate-validate="false"
  :options="options"
  @validate="validateHandler"
  @submit="submitHandler"
  @reset="resetHandler"></cube-form>


	
</div>
</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<!-- 引入组件库 -->
<script src="{{ asset('statics/cube/cube.min.js') }}"></script>



<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {

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


        validity: {},
        valid: undefined,
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

        // showPicker
		showPicker1() {
            if (!this.picker1) {
                this.picker1 = this.$createPicker({
                title: 'Picker1',
                data: [this.column1],
                onSelect: this.selectHandle,
                onCancel: this.cancelHandle
                })
            }
            this.picker1.show()
        },
        selectHandle(selectedVal, selectedIndex, selectedText) {
        this.$createDialog({
            type: 'warn',
            content: `Selected Item: <br/> - value: ${selectedVal.join(', ')} <br/> - index: ${selectedIndex.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            icon: 'cubeic-alert'
        }).show()
        },
        cancelHandle() {
            this.$createToast({
                type: 'correct',
                txt: 'Picker canceled',
                time: 1000
            }).show()
        },

		showPicker2() {
            if (!this.picker2) {
                this.picker2 = this.$createPicker({
                title: 'Picker2',
                data: [this.column2],
                onSelect: this.selectHandle2,
                onCancel: this.cancelHandle2
                })
            }
            this.picker2.show()
        },
        selectHandle2(selectedVal, selectedIndex, selectedText) {
        this.$createDialog({
            type: 'warn',
            content: `Selected Item: <br/> - value: ${selectedVal.join(', ')} <br/> - index: ${selectedIndex.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            icon: 'cubeic-alert'
        }).show()
        },
        cancelHandle2() {
            this.$createToast({
                type: 'correct',
                txt: 'Picker canceled',
                time: 1000
            }).show()
        },


        // showDateTimePicker
        showDateTimePicker() {
            if (!this.dateTimePicker) {
                this.dateTimePicker = this.$createDatePicker({
                title: 'Date Time Picker',
                min: new Date(2008, 7, 8, 8, 0, 0),
                max: new Date(2020, 9, 20, 20, 59, 59),
                value: new Date(),
                columnCount: 6,
                onSelect: this.selectHandle,
                onCancel: this.cancelHandle
                })
            }

            this.dateTimePicker.show()
        },
        selectHandle(date, selectedVal, selectedText) {
            this.$createDialog({
                type: 'warn',
                content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
                icon: 'cubeic-alert'
            }).show()
        },
        cancelHandle() {
            this.$createToast({
                type: 'correct',
                txt: 'Picker canceled',
                time: 1000
            }).show()
        },
        

        // form
        submitHandler(e) {
            e.preventDefault()
            console.log('submit', e)
        },
        validateHandler(result) {
            this.validity = result.validity
            this.valid = result.valid
            console.log('validity', result.validity, result.valid, result.dirty, result.firstInvalidFieldIndex)
        },
        resetHandler(e) {
            console.log('reset', e)
        },

		



	},
	mounted: function () {

	}
})
</script>
</html>