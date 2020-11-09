
class IEntry:
    """
     * Entry interface - this will be shared across the projects
    """

    SOURCE_CLI = 'cli'
    SOURCE_GET = 'get'
    SOURCE_POST = 'post'
    SOURCE_FILES = 'files'
    SOURCE_SESSION = 'session'
    SOURCE_SERVER = 'server'
    SOURCE_ENV = 'environment'
    SOURCE_EXTERNAL = 'external'

    def get_source(self) -> str:
        """
         * Return source of entry
        """
        raise NotImplementedError('TBA')

    def get_key(self) -> str:
        """
         * Return key of entry
        """
        raise NotImplementedError('TBA')

    def get_value(self):
        """
         * Return value of entry
         * It could be anything - string, boolean, array - depends on source
        """
        raise NotImplementedError('TBA')


class IFileEntry(IEntry):
    """
     * File entry interface - how to access uploaded files
     * @link https://www.php.net/manual/en/reserved.variables.files.php
    """

    def get_mime_type(self) -> str:
        """
         * Return what mime is that by browser
         * Beware, it is not reliable
        """
        raise NotImplementedError('TBA')

    def get_temp_name(self) -> str:
        """
         * Get name in temp
         * Use it for function like move_uploaded_file()
        """
        raise NotImplementedError('TBA')

    def get_error(self) -> int:
        """
         * Get error code from upload
         * @link https://www.php.net/manual/en/features.file-upload.errors.php
        """
        raise NotImplementedError('TBA')

    def get_size(self) -> int:
        """
         * Get uploaded file size
        """
        raise NotImplementedError('TBA')


class Entry(IEntry):
    """
     * Simple entry from source
     * For setting numeric value just re-type set by strval()
     * For setting boolean value just expand previous - strval(intval())
    """

    _available_sources = [
        IEntry.SOURCE_CLI,
        IEntry.SOURCE_GET,
        IEntry.SOURCE_POST,
        # IEntry::SOURCE_FILES,  # has own class
        IEntry.SOURCE_SESSION,
        IEntry.SOURCE_SERVER,
        IEntry.SOURCE_ENV,
        IEntry.SOURCE_EXTERNAL,
    ]

    def __init__(self):
        self._key = ''
        self._value = ''
        self._source = ''

    def set_entry(self, source: str, key: str, value=None):
        self._key = key
        self._value = value
        self._source = self._available_source(source)
        return self

    def _available_source(self, source: str) -> str:
        return source if source in Entry._available_sources else self._source

    def get_source(self) -> str:
        return self._source

    def get_key(self) -> str:
        return self._key

    def get_value(self):
        return self._value


class FileEntry(IFileEntry, Entry):
    """
     * Input is file and has extra values
    """

    def __init__(self):
        super().__init__()
        self._mime_type = ''
        self._temp_name = ''
        self._error = 0
        self._size = 0

    def set_file(self, file_name: str, temp_name: str, mime_type: str, error: int, size: int):
        self._value = file_name
        self._mime_type = mime_type
        self._temp_name = temp_name
        self._error = error
        self._size = size
        return self

    def get_source(self) -> str:
        return self.SOURCE_FILES

    def get_mime_type(self) -> str:
        return self._mime_type

    def get_temp_name(self) -> str:
        return self._temp_name

    def get_error(self) -> int:
        return self._error

    def get_size(self) -> int:
        return self._size
