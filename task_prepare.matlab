image_path='images/';
names = struct([]);
list_dir = dir(fullfile(image_path , '*' ));
for i=1:length(list_dir)
    list_files = dir([image_path list_dir(i).name '/*.jpg']);
    for j=1:length(list_files)
        list_files(j).author = list_dir(i).name;
    end
    if isempty(names) 
        names = list_files;
    else
        names = [names; list_files];
    end
end

artists= unique({names.author});
for i=1:length(artists)
    label_map(strcmp({names.author}, artists(i))) = i;
end

rows=length(label_map);
fileID=fopen('image_src.csv', 'wt');
for i=1:rows
	fprintf(fileID, '%d,%d,',[i,label_map(i)]);
	fprintf(fileID, '%s/%s\n', names(i).author, names(i).name);
end
fclose(fileID);

cols=length(artists);
pkg load statistics
num=3000;
num_img_per_task=3;
fileID=fopen('task_src.csv', 'wt');
for i=1:num
	people=randsample(cols, num_img_per_task-1);
	two=randsample(find(label_map==people(1)), 2);
	fprintf(fileID, '%d,"[%d,%d', i, two(1), two(2));
	for j=2:(num_img_per_task-1)
		rest=randsample(find(label_map==people(j)), 1);
		fprintf(fileID, ',%d', rest);
	end
	fprintf(fileID, ']", %d\n', people(1));
end
fclose(fileID);
