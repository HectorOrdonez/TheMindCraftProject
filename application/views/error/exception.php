<div class='exception'>
    <table class="exceptionTable">
        <tbody>
            <tr class="exceptionRow">
                <td class="exceptionLabel">
                    Exception
                </td>
                <td class="exceptionValue"><?php echo $this->exception;?></td>
            </tr>
            <tr>
                <td class="exceptionLabel">
                    File
                </td>
                <td class="exceptionValue"><?php echo $this->file;?></td>
            </tr>
            <tr>
                <td class="exceptionLabel">
                    Line
                </td>
                <td class="exceptionValue"><?php echo $this->line;?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class='exceptionTrack'>
<?php foreach($this->backtrace as $traces): ?>
    <table class="backtraceTable">
<?php foreach($traces as $tracedField=>$tracedValue): ?>
        <tr class="backtraceRow">
            <td class="backtraceLabel"><?php echo $tracedField;?></td>
            <td class="backtraceValue"><?php echo $tracedValue;?></td>
        </tr>
<?php endforeach; ?>
    </table>
<?php endforeach; ?>
</div>