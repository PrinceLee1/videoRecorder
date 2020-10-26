<template>
    <div>
        <video id="myVideo" playsinline class="video-js vjs-default-skin" >
                 <p class="vjs-no-js">
                To view this video please enable JavaScript, or consider upgrading to a
                web browser that
                <a href="https://videojs.com/html5-video-support/" target="_blank">
                    supports HTML5 video.
                </a>
            </p>
        </video>
                <!-- <video id="vid2" controls v-if="isStartRecording"></video> -->

        <br>

        <button type="button" class="btn btn-info" @click.prevent="startRecording()" v-bind:disabled="isStartRecording" id="btnStart">Start Recording</button>
        <button type="button" class="btn btn-success" @click="submitVideo()" v-bind:disabled="isSaveDisabled" id="btnSave">{{ submitText }}</button>
        <button type="button" class="btn btn-primary" @click.prevent="retakeVideo()" v-bind:disabled="isRetakeDisabled" id="btnRetake">Retake</button>
    </div>
</template>

<script>
import 'video.js/dist/video-js.css'
import 'videojs-record/dist/css/videojs.record.css'
import videojs from 'video.js'
import 'webrtc-adapter'
import RecordRTC from 'recordrtc'
import Record from 'videojs-record/dist/videojs.record.js'
import FFmpegjsEngine from 'videojs-record/dist/plugins/videojs.record.ffmpegjs.js';
export default {
    props: ['uploadUrl'],


    data() {
        return {
            player: '',
            retake: 0,
            isSaveDisabled: true,
            isStartRecording: false,
            isRetakeDisabled: true,
            submitText: 'Submit Video',
            options: {
                controls: true,
                bigPlayButton: false,
                controlBar: {
                    deviceButton: false,
                    recordToggle: false,
                    pipToggle: false,
                    download:true
                },
                width: 300,
                height: 200,
                fluid: true,
                plugins: {
                    record: {
                        pip: false,
                        audio: true,
                        video: true,
                        maxLength: 5,
                        debug: true
                    }
                }
            }
        }
    },
    mounted() {
        this.player = videojs('myVideo', this.options, () => {
            // print version information at startup
            var msg = 'Using video.js ' + videojs.VERSION +
                ' with videojs-record ' + videojs.getPluginVersion('record') +
                ' and recordrtc ' + RecordRTC.version;
            videojs.log(msg);
        });
        // error handling
        this.player.on('deviceReady', () => {
            this.player.record().start();
            console.log('device ready:');
        });
        this.player.on('deviceError', () => {
            console.log('device error:', this.player.deviceErrorCode);
        });
        this.player.on('error', (element, error) => {
            console.error(error);
        });
        // user clicked the record button and started recording
        this.player.on('startRecord', () => {
            console.log('started recording!');
        });
        // user completed recording and stream is available
        this.player.on('finishRecord', () => {
            this.isSaveDisabled = false;
            if(this.retake == 0) {
                this.isRetakeDisabled = false;
            }
            // the blob object contains the recorded data that
            console.log('finished recording: ', this.player.recordedData);
        });
    },
    methods: {
        startRecording() {
            this.isStartRecording = true;
            this.player.record().getDevice();
        },
        submitVideo() {
            this.isSaveDisabled = true;
            this.isRetakeDisabled = true;
            var data = this.player.recordedData;
            //   const videoBlob = new Blob(data, {type: 'video/webm'});
                // this.videoBlob = videoBlob
            // console.log(videoBlob)
            var formData = new FormData();
            var newData = {
                data_name: data.name,
                data_type: data.type,
                data_size: data.size
            }
//                    for(key in newData) {
//     if(newData.hasOwnProperty(key)) {
//         var value = newData[key];
//         console.log(value)
//         //do something with value;
//     }
// }

            formData.append('file', data);

            this.submitText = "Uploading "+data.name;
            console.log('uploading recording:', data.name);
            this.player.record().stopDevice();

            axios.post('/saveVideo', formData)
                .then((response) => {

                console.log('recording upload completed');
             this.submitText = "Upload Complete";
                })
                .catch((error) => {
             console.error('an upload error occurred!');
                    this.submitText = "Upload Failed";

                });
//                $.ajax({
//         url : '/saveBlob',
//         type : 'POST',
//          cache : false,
//     processData: false,
//         data : {
//               body: formData,
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                  videoFiles: this.player.recordedData
//         },
//         success : function(){
//     console.log('recording upload completed');
//     this.submitText = "Upload Comple";
//         },
//         error: function() {
//             console.log('an upload error occured');
//             this.submitText = "Upload Failed";
//         }
//       //console.log(images);

//   });
//             fetch('/saveBlob', {
//                 method: 'POST',
//                 body: formData,
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                  videoFiles: this.player.recordedData
//             }).then(
//                 success => {
//                     console.log('recording upload complete.');

//  this.submitText = "Upload Complete";
//                 }
//             ).catch(
//                 error =>{
//                     console.error('an upload error occurred!');
//                     this.submitText = "Upload Failed";
//                 }
//             );
        },
        retakeVideo() {
            this.isSaveDisabled = true;
            this.isRetakeDisabled = true;
            this.retake += 1;
            this.player.record().start();
        }
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    }
}

</script>
