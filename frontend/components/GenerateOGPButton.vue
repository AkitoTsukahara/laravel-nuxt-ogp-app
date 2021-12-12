<template>
  <div class="warp">
    <div class="button is-primary" ref="capture">
      OGPイメージを作成する
    </div>
  </div>
</template>

<script>
export default {
  name: 'GenerateOGPButton',
  mounted() {
    const elm = this.$refs.capture
    const self = this

    this.$nextTick(() => {
      if (process.client) {
        const h2c = require("html2canvas")
        elm.addEventListener("click", ()=> {
          self.$store.dispatch("setLoading", true);
          window.scrollTo(0, 0);
          h2c(document.querySelector("#js_capture_ref"), {
            backgroundColor: "#fff"
          }).then(canvas => {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onload = function() {
              const dataURI = this.result;
              self.handleClick(dataURI);
            }
          })
        })
      }
    })
  },
  methods: {
    handleClick(dataURI) {
      this.$emit("click", dataURI)
    }
  }
};
</script>

<style scoped>
.warp {
  text-align: center;
}
</style>
