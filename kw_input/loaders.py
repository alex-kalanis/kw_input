from kw_input.interfaces import IEntry
from kw_input.entries import Entry as EntryItem
from kw_input.entries import FileEntry as FileEntryItem


class ALoader:
    """
     * Load input arrays into normalized entries
    """

    def load_vars(self, source: str, array):
        """
         * Transform input values to something more reliable
         * @return Entry[]
        """
        raise NotImplementedError('TBA')


class Entry(ALoader):
    """
     * Load input arrays into normalized entries
    """

    def load_vars(self, source: str, array):
        """
         * Transform input values to something more reliable
         * @return Entry[]
        """
        result = []
        for (key, val) in array:
            result.append(EntryItem().set_entry(source, key, val))
        return result


class File(ALoader):
    """
     * Load file input array into normalized entries
     * @link https://www.php.net/manual/en/reserved.variables.files.php
    """

    def load_vars(self, source: str, array):
        result = []
        for (posted_key, posted) in array:
            post_dict = dict(posted)
            if isinstance(post_dict['name'], (list, dict, tuple)):
                for (key, value) in post_dict['name']:
                    entry = FileEntryItem()
                    entry.set_entry(source, '%s[%s]' % (posted_key, key))
                    tmp_name_dict = dict(post_dict['tmp_name'])
                    type_dict = dict(post_dict['type'])
                    error_dict = dict(post_dict['error'])
                    size_dict = dict(post_dict['size'])
                    entry.set_file(
                        value,
                        tmp_name_dict[key],
                        type_dict[key],
                        int(error_dict[key]),
                        int(size_dict[key])
                    )
                    result.append(entry)
            else:
                entry = FileEntryItem()
                entry.set_entry(source, posted_key)
                entry.set_file(
                    post_dict['name'],
                    post_dict['tmp_name'],
                    post_dict['type'],
                    int(post_dict['error']),
                    int(post_dict['size'])
                )
                result.append(entry)
        return result


class Factory:
    """
     * Loading factory
    """

    _loaders = {}

    def get_loader(self, source: str) -> ALoader:
        if source in Factory._loaders.keys():
            return Factory._loaders[source]

        loader = self._select(source)
        Factory._loaders[source] = loader
        return loader

    def _select(self, source: str) -> ALoader:
        if IEntry.SOURCE_FILES == source:
            return File()
        else:
            return Entry()
