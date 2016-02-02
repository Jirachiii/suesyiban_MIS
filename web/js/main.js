//内容过长，部分显示
function handleLength(data, maxNum) {
	if (data.length > 5) {
		data = data.substring(0,maxNum);
		data += '...';
	}
	return data;
}