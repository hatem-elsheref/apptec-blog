self.addEventListener('message', function(event) {

    (async function() {
        // Get the form data from the message
        let post = event.data[0].post;
        let video = event.data[1];

        // Get the file data from the ReadableStream
        let fileReader = video.stream().getReader();
        let chunkSize = 1024 * 1024 * 2 ; // 2 MB
        let offset = 0;
        let chunk = await fileReader.read(chunkSize);
        // Upload the file in chunks

        let formData = new FormData();
        formData.append('video', post.new_video);
        formData.append('post', post.id);

        while (!chunk.done) {
            let chunkData = chunk.value;
            let blob = new Blob([chunkData], { type: 'application/octet-stream' });
            let end = offset + chunkData.byteLength - 1;
            formData.set('content', blob);
            formData.append('offset', offset);
            formData.append('size', video.size);
            formData.append('end', end + 1);
            await fetch('/admin/upload', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    // 'Content-Type': 'application/octet-stream',
                    'Content-Range': 'bytes ' + offset + '-' + end + '/' + video.size
                },
                body: formData
            });
            offset += chunkData.byteLength;
            chunk = await fileReader.read(chunkSize);
        }
        self.postMessage('ok')
        console.log('File uploaded successfully!');
    })()

});
