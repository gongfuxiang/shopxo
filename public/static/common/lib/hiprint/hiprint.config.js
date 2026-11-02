(function () {
    window.HIPRINT_CONFIG = {
        //optionItems: [hiprintCustomOptionItem],//自定义选项
        movingDistance: 1.5, //鼠标拖动一次移动的距离,默认1.5pt
        paperHeightTrim: 1, //纸张html 的高度等于真实高度-1
        text: any = {
            supportOptions: [
                // {
                //     name: 'hiprintCustomOptionItem',
                //     hidden: false
                // },
                {
                    name: 'title',
                    hidden: false
                },
                {
                    name: 'field',
                    hidden: false
                },
                {
                    name: 'testData',
                    hidden: false
                },
                {
                    name: 'dataType',
                    hidden: false
                },
                {
                    name: 'fontFamily',
                    hidden: false
                },
                {
                    name: 'fontSize',
                    hidden: false
                },
                {
                    name: 'fontWeight',
                    hidden: false
                },
                {
                    name: 'letterSpacing',
                    hidden: false
                },
                {
                    name: 'color',
                    hidden: false
                },
                {
                    name: 'textDecoration',
                    hidden: false
                },
                {
                    name: 'textAlign',
                    hidden: false
                },
                {
                    name: 'textContentVerticalAlign',
                    hidden: false
                },

                {
                    name: 'lineHeight',
                    hidden: false
                },
                {
                    name: 'textType',
                    hidden: false
                },
                {
                    name: 'barcodeMode',
                    hidden: false
                },
                {
                    name: 'hideTitle',
                    hidden: false
                },
                {
                    name: 'showInPage',
                    hidden: false
                },
                {
                    name: 'unShowInPage',
                    hidden: false
                },
                {
                    name: 'fixed',
                    hidden: false
                },
                {
                    name: 'axis',
                    hidden: false
                },
                {
                    name: 'transform',
                    hidden: false
                },
                {
                    name: 'optionsGroup',
                    hidden: false
                },
                {
                    name: 'borderLeft',
                    hidden: false
                },
                {
                    name: 'borderTop',
                    hidden: false
                },
                {
                    name: 'borderRight',
                    hidden: false
                },
                {
                    name: 'borderBottom',
                    hidden: false
                },
                {
                    name: 'borderWidth',
                    hidden: false
                },
                {
                    name: 'borderColor',
                    hidden: false
                },
                {
                    name: 'contentPaddingLeft',
                    hidden: false
                },
                {
                    name: 'contentPaddingTop',
                    hidden: false
                },
                {
                    name: 'contentPaddingRight',
                    hidden: false
                },
                {
                    name: 'contentPaddingBottom',
                    hidden: false
                },
                {
                    name: 'backgroundColor',
                    hidden: false
                },
                {
                    name: 'formatter',
                    hidden: false
                },
                {
                    name: 'styler',
                    hidden: false
                }

            ],
            default: {
                width: 120,
                height: 9.75,
            }
        },
        image: any = {
            supportOptions: [{
                name: 'field',
                hidden: false
            },
            {
                name: 'src',
                hidden: false
            },
            {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'transform',
                hidden: false
            },
            {
                name: 'formatter',
                hidden: false
            },
            {
                name: 'styler',
                hidden: false
            }
            ],
            default: {

            }
        },
        longText: any = {
            supportOptions: [{
                name: 'title',
                hidden: false
            },
            {
                name: 'field',
                hidden: false
            },
            {
                name: 'testData',
                hidden: false
            },
            {
                name: 'fontFamily',
                hidden: false
            },
            {
                name: 'fontSize',
                hidden: false
            },
            {
                name: 'fontWeight',
                hidden: false
            },
            {
                name: 'letterSpacing',
                hidden: false
            },

            {
                name: 'textAlign',
                hidden: false
            },
            {
                name: 'lineHeight',
                hidden: false
            },
            {
                name: 'color',
                hidden: false
            },
            {
                name: 'hideTitle',
                hidden: false
            },

            {
                name: 'longTextIndent',
                hidden: false
            },

            {
                name: 'leftSpaceRemoved',
                hidden: false
            },
            {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'unShowInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'lHeight',
                hidden: false
            },
            {
                name: 'transform',
                hidden: false
            },
            {
                name: 'optionsGroup',
                hidden: false
            },
            {
                name: 'borderLeft',
                hidden: false
            },
            {
                name: 'borderTop',
                hidden: false
            },
            {
                name: 'borderRight',
                hidden: false
            },
            {
                name: 'borderBottom',
                hidden: false
            },
            {
                name: 'borderWidth',
                hidden: false
            },
            {
                name: 'borderColor',
                hidden: false
            },
            {
                name: 'contentPaddingLeft',
                hidden: false
            },
            {
                name: 'contentPaddingTop',
                hidden: false
            },
            {
                name: 'contentPaddingRight',
                hidden: false
            },
            {
                name: 'contentPaddingBottom',
                hidden: false
            },
            {
                name: 'backgroundColor',
                hidden: false
            },
            {
                name: 'formatter',
                hidden: false
            },
            {
                name: 'styler',
                hidden: false
            }

            ],
            default: {
                height: 42,
                width: 550
            }
        },
        table: any = {
            supportOptions: [{
                name: 'field',
                hidden: false
            },
            {
                name: 'fontFamily',
                hidden: false
            },
            {
                name: 'fontSize',
                hidden: false
            },
            {
                name: 'lineHeight',
                hidden: false
            },
            {
                name: 'textAlign',
                hidden: false
            },
            {
                name: 'gridColumns',
                hidden: false
            },
            {
                name: 'gridColumnsGutter',
                hidden: false
            },

            {
                name: 'tableBorder',
                hidden: false
            },
            {
                name: 'tableHeaderBorder',
                hidden: false
            },
            {
                name: 'tableHeaderCellBorder',
                hidden: false
            },
            {
                name: 'tableHeaderRowHeight',
                hidden: false
            },
            {
                name: 'tableHeaderBackground',
                hidden: false
            },
            {
                name: 'tableHeaderFontSize',
                hidden: false
            },
            {
                name: 'tableHeaderFontWeight',
                hidden: false
            },

            {
                name: 'tableBodyRowHeight',
                hidden: false
            },
            {
                name: 'tableBodyRowBorder',
                hidden: false
            },
            {
                name: 'tableBodyCellBorder',
                hidden: false
            },

            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'lHeight',
                hidden: false
            },

            {
                name: 'autoCompletion',
                hidden: false
            },
            {
                name: 'columns',
                hidden: false
            },
            {

                name: 'styler',
                hidden: false
            },
            {

                name: 'rowStyler',
                hidden: false
            },

            {

                name: 'tableFooterRepeat',
                hidden: false
            },
            {

                name: 'footerFormatter',
                hidden: false
            },
            {
                name: 'gridColumnsFooterFormatter',
                hidden: false

            }


            ],
            default: {

                width: 550
            }
        },
        tableCustom: any = {
            supportOptions: [{
                name: 'field',
                hidden: false
            },
            {
                name: 'fontFamily',
                hidden: false
            },
            {
                name: 'fontSize',
                hidden: false
            },
            {
                name: 'textAlign',
                hidden: false
            },
            {
                name: 'tableBorder',
                hidden: false
            },
            {
                name: 'tableHeaderBorder',
                hidden: false
            },
            {
                name: 'tableHeaderCellBorder',
                hidden: false
            },
            {
                name: 'tableHeaderRowHeight',
                hidden: false
            },
            {
                name: 'tableHeaderFontSize',
                hidden: false
            },
            {
                name: 'tableHeaderFontWeight',
                hidden: false
            },
            {
                name: 'tableHeaderBackground',
                hidden: false
            },

            {
                name: 'tableBodyRowHeight',
                hidden: false
            },
            {
                name: 'tableBodyRowBorder',
                hidden: false
            },
            {
                name: 'tableBodyCellBorder',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'lHeight',
                hidden: false
            },
            {
                name: 'autoCompletion',
                hidden: false
            }, {

                name: 'tableFooterRepeat',
                hidden: false
            }
            ],
            default: {

                width: 550
            }
        },


        hline: any = {
            supportOptions: [{
                name: 'borderColor',
                hidden: false
            }, {
                name: 'borderWidth',
                hidden: false
            }, {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'transform',
                hidden: false
            },
            {
                name: 'borderStyle',
                hidden: false
            }

            ],
            default: {
                borderWidth: 0.75,
                height: 9,
                width: 90
            }
        },
        vline: any = {
            supportOptions: [{
                name: 'borderColor',
                hidden: false
            }, {
                name: 'borderWidth',
                hidden: false
            }, {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'transform',
                hidden: false
            },
            {
                name: 'borderStyle',
                hidden: false
            }
            ],
            default: {
                borderWidth: undefined,
                height: 90,
                width: 9
            }
        },
        rect: any = {
            supportOptions: [{
                name: 'borderColor',
                hidden: false
            }, {
                name: 'borderWidth',
                hidden: false
            }, {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'transform',
                hidden: false
            },
            {
                name: 'borderStyle',
                hidden: false
            }
            ],
            default: {
                borderWidth: undefined,
                height: 90,
                width: 90
            }
        },
        oval: any = {
            supportOptions: [{
                name: 'borderColor',
                hidden: false
            }, {
                name: 'borderWidth',
                hidden: false
            }, {
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            }, {
                name: 'transform',
                hidden: false
            },
            {
                name: 'borderStyle',
                hidden: false
            }
            ],
            default: {
                borderWidth: undefined,
                height: 90,
                width: 90
            }
        },
        html: any = {
            supportOptions: [{
                name: 'showInPage',
                hidden: false
            },
            {
                name: 'unShowInPage',
                hidden: false
            },
            {
                name: 'fixed',
                hidden: false
            },
            {
                name: 'axis',
                hidden: false
            },
            {
                name: 'formatter',
                hidden: false
            }
            ],
            default: {

                height: 90,
                width: 90
            }
        },
        tableColumn: any = {
            supportOptions: [
                {
                    name: 'title',
                    hidden: false
                },
                {
                    name: 'align',
                    hidden: false
                },
                {
                    name: 'halign',
                    hidden: false
                },
                {
                    name: 'vAlign',
                    hidden: false
                },


                {
                    name: 'paddingLeft',
                    hidden: false
                },
                {
                    name: 'paddingRight',
                    hidden: false
                },
                {
                    name: 'formatter2',
                    hidden: false
                }, {
                    name: 'styler2',
                    hidden: false
                }
            ],
            default: {

                height: 90,
                width: 90
            }
        }
    }
})();