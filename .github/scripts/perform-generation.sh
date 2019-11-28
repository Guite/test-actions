#!/bin/sh

# create working directory
mkdir -p work
cd work
wget https://updates.modulestudio.de/standalone/ModuleStudio-generator.jar

echo "Fetch model and regenerate it"
cp ../modules/$MODULE_NAME/Resources/docs/model/$MODEL_NAME ./$MODEL_NAME
java -jar ModuleStudio-generator.jar $MODEL_NAME output

echo "Remove unrequired files and copy new module into the checkout"
outputPath="output/$MODULE_NAME"
rm $outputPath/Resources/public/images/*.png
find $outputPath -type f -name '*.generated.*' -delete
cp -R $outputPath/* ../modules/$MODULE_NAME
cd ..
