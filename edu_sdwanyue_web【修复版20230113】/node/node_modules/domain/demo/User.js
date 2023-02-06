var Domain = require(".."),
    FreezeMoney = require("./FreezeMoney"),
    Root = Domain.AggregateRoot;

module.exports = Root.extend({

    constructor: function (data) {
        this.set("money", 0.0);
        this.set("freezeMoneyList", []);
    },

    methods: {
        recharge: function (data,apply) {
            // validat code omitted
            apply("recharge", data);
        },
        deduct: function (data,apply) {
            // validat code omitted
            apply("deduct", data);
        },
        finish: function (data,apply) {
            // validate code omitted
            apply("finish", data);
        },
        cancel: function (data,apply) {
            // validate code omitted
            apply("cancel", data);
        }
    },
    when: function (event) {
        switch (event.name) {
            case "recharge":
                var fm = new FreezeMoney(event.data.id, event.data.money);
                var freezeMoneyList = this.get("freezeMoneyList");
                freezeMoneyList.push(fm);
                this.set("freezeMoneyList", freezeMoneyList);
                break;

            case "deduct":
                var fm = new FreezeMoney(event.data.id, -event.data.money);
                var money = this.get("money");
                this.set("money", money - event.data.money);
                var freezeMoneyList = this.get("freezeMoneyList");
                freezeMoneyList.push(fm);
                break;

            case "cancel":
                var self = this;
                var fmIndex = -1;
                var fmid = event.data.id;
                var freezeMoneyList = this.get("freezeMoneyList");
                // find by fmid
                freezeMoneyList.forEach(function (fm, index) {
                    if (fm.id === fmid) {

                        if (fm.money < 0) {
                            var money = self.get("money");

                            self.set("money", money - fm.money);
                        }
                        fmIndex = index;
                    }
                })

                if (fmIndex !== -1) {
                    freezeMoneyList.splice(fmIndex, 1);
                }

                break;

            case "finish":
                var self = this;
                var fmIndex = -1;
                var fmid = event.data.id;
                var freezeMoneyList = this.get("freezeMoneyList");
                // find by fmid
                freezeMoneyList.forEach(function (fm, index) {
                    if (fm.id === fmid) {
                        if (fm.money > 0) {
                            var money = self.get("money");
                            self.set("money", money + fm.money);
                        }
                        fmIndex = index;
                    }
                })

                if (fmIndex !== -1) {
                    freezeMoneyList.splice(fmIndex, 1);
                }

                break;
        }
    },

    loadSnap: function (json) {
        this.set("money", json.money);
        var freezeMoneyList = [];
        json.freezeMoneyList.forEach(function (fm) {
            freezeMoneyList.push(new FreezeMoney(fm.id, fm.money));
        });
        this.set("freezeMoneyList", freezeMoneyList);
    }
})